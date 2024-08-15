<?php

namespace Drupal\permissions_by_term\Service;

use Drupal\Component\Utility\Environment;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\node\Entity\Node;
use Drupal\permissions_by_term\Cache\CacheInvalidator;
use Drupal\permissions_by_term\Factory\NodeAccessRecordFactory;
use Drupal\permissions_by_term\Model\NodeAccessRecordModel;
use Drupal\user\Entity\User;

/**
 * Service class for node access management.
 *
 * @package Drupal\permissions_by_term
 */
class NodeAccess {

  /**
   * @var int $uniqueGid
   */
  private $uniqueGid = 0;

  /**
   * @var \Drupal\permissions_by_term\Service\AccessStorage
   */
  private $accessStorage;

  /**
   * @var \Drupal\user\UserInterface
   */
  private $userEntityStorage;

  /**
   * @var \Drupal\node\NodeInterface
   */
  private $node;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * @var \Drupal\permissions_by_term\Service\AccessCheck
   */
  private $accessCheck;

  /**
   * @var int
   */
  private $loadedUid;

  /**
   * @var \Drupal\user\UserInterface
   */
  private $userInstance;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  private $database;

  /**
   * The node access record factory.
   *
   * @var \Drupal\permissions_by_term\Factory\NodeAccessRecordFactory
   */
  private $nodeAccessRecordFactory;

  /**
   * The entity field manager.
   * 
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  private $entityFieldManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * The current path stack.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  private $currentPath;

  /**
   * The cache invalidator.
   *
   * @var \Drupal\permissions_by_term\Cache\CacheInvalidator
   */
  private $cacheInvalidator;

  /**
   * NodeAccess constructor.
   *
   * @param \Drupal\permissions_by_term\Service\AccessStorage $accessStorage
   * @param \Drupal\permissions_by_term\Factory\NodeAccessRecordFactory $nodeAccessRecordFactory
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\permissions_by_term\Service\AccessCheck $accessCheck
   * @param \Drupal\Core\Database\Connection $database
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   * @param \Drupal\Core\Path\CurrentPathStack $currentPath
   * @param \Drupal\permissions_by_term\Cache\CacheInvalidator $cacheInvalidator
   */
  public function __construct(
    AccessStorage $accessStorage,
    NodeAccessRecordFactory $nodeAccessRecordFactory,
    EntityTypeManagerInterface $entityTypeManager,
    AccessCheck $accessCheck,
    Connection $database,
    EntityFieldManagerInterface $entityFieldManager,
    ConfigFactoryInterface $configFactory,
    CurrentPathStack $currentPath,
    CacheInvalidator $cacheInvalidator
  ) {
    $this->accessStorage = $accessStorage;
    $this->nodeAccessRecordFactory = $nodeAccessRecordFactory;
    $this->entityTypeManager = $entityTypeManager;
    $this->userEntityStorage = $this->entityTypeManager->getStorage('user');
    $this->node = $this->entityTypeManager->getStorage('node');
    $this->accessCheck = $accessCheck;
    $this->database = $database;
    $this->entityFieldManager = $entityFieldManager;
    $this->configFactory = $configFactory;
    $this->currentPath = $currentPath;
    $this->cacheInvalidator = $cacheInvalidator;
  }

  /**
   * @return \Drupal\permissions_by_term\Model\NodeAccessRecordModel
   */
  public function createGrant($nid, $gid) {
    return $this->nodeAccessRecordFactory->create(
      AccessStorage::NODE_ACCESS_REALM,
      $gid,
      $nid,
      $this->accessStorage->getLangCode($nid),
      0,
      0
    );
  }

  /**
   * @return int
   */
  public function getUniqueGid() {
    return $this->uniqueGid;
  }

  /**
   * @param int $uniqueGid
   */
  public function setUniqueGid($uniqueGid) {
    $this->uniqueGid = $uniqueGid;
  }

  public function canUserBypassNodeAccess($uid) {
    $user = $this->getUserInstance($uid);
    if ($user->hasPermission('bypass node access')) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param $uid
   * @param $nodeType
   * @param $nid
   *
   * @return bool
   */
  public function canUserDeleteNode($uid, $nodeType, $nid) {
    $user = $this->getUserInstance($uid);
    if ($user->hasPermission('delete any ' . $nodeType . ' content')) {
      return TRUE;
    }

    if ($this->isNodeOwner($nid, $uid) && $this->canDeleteOwnNode($uid, $nodeType)) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param $nid
   * @param $uid
   *
   * @return bool
   */
  public function isNodeOwner($nid, $uid) {
    $node = $this->node->load($nid);
    if ((int)$node->getOwnerId() == (int)$uid) {
      return TRUE;
    }

    return FALSE;
  }

  public function getNidsForAccessRebuild(array $tidsInvolved = []): array {
    // We do not use taxonomy_index table here. The taxonomy_index table is
    // populated only if an node is published. PbT has fetched all term
    // id to node id relations via this table. That's wrong because Permission
    // by Term is managing also permissions for unpublished nodes.
    // We also don't want to reevaluate every node's permissions - on a
    // database with millions of nodes that takes hours. So we now take an
    // array of terms that have been added to or removed from a user or role
    // and locate for the nodes that use those terms.
    $nodeTypeStorage = $this->entityTypeManager->getStorage('node_type');
    $nodeTypes = $nodeTypeStorage->loadMultiple();

    $vocabsUsed = $this->configFactory->get('permissions_by_term.settings')
      ->get('target_bundles');
    $nids = [];

    foreach ($nodeTypes as $nodeType) {
      $fields = $this->entityFieldManager->getFieldDefinitions('node', $nodeType->id());
      foreach ($fields as $fieldName => $field) {
        if ($field->getType() !== 'entity_reference') {
          continue;
        }

        $definition = $field->getItemDefinition();
        if ($definition->getSetting('target_type') !== 'taxonomy_term') {
          continue;
        }

        $handler_settings = $definition->getSetting('handler_settings');
        if (!empty($handler_settings['target_bundles']) && !empty($vocabsUsed) &&
          empty(array_intersect($handler_settings['target_bundles'], $vocabsUsed))) {
          continue;
        }

        $mapping = $this->entityTypeManager->getStorage('node')
          ->getTableMapping()->getAllFieldTableNames($fieldName);

        foreach ($mapping as $table) {
          $query = $this->database->select($table, 't')
            ->condition('bundle', $nodeType->id())
            ->condition('deleted', 0)
            ->fields('t', ['entity_id']);

          // If we can filter to a list of tids we care about, do so.
          // Otherwise we use all nids that have a tid reference (which is
          // at least potentially still better than all nids).

          if (!empty($tidsInvolved)) {
            $query->condition($field->getName() . '_target_id', $tidsInvolved, 'IN');
          }

          $matches = array_unique($query->execute()->fetchCol());
          $nids = array_unique(array_merge($nids, $matches));
        }
      }
    }
    return $nids;
  }

  private function canUpdateOwnNode($uid, $nodeType) {
    $user = $this->getUserInstance($uid);
    if ($user->hasPermission('edit own ' . $nodeType . ' content')) {
      return 1;
    }

    return 0;
  }

  private function canDeleteOwnNode($uid, $nodeType) {
    $user = $this->getUserInstance($uid);
    if ($user->hasPermission('delete own ' . $nodeType . ' content')) {
      return 1;
    }

    return 0;
  }

  /**
   * @return int
   */
  public function getLoadedUid() {
    return $this->loadedUid;
  }

  /**
   * @param int $loadedUid
   */
  public function setLoadedUid($loadedUid) {
    $this->loadedUid = $loadedUid;
  }

  /**
   * @return User
   */
  public function getUserInstance($uid) {
    if ($this->getLoadedUid() !== $uid) {
      $user = $this->userEntityStorage->load($uid);
      $this->setUserInstance($user);
      return $user;
    }

    return $this->userInstance;
  }

  /**
   * @param User $userInstance
   */
  public function setUserInstance($userInstance) {
    $this->userInstance = $userInstance;
  }

  /**
   * @param int $nid
   *
   * @return bool
   */
  public function isAccessRecordExisting($nid) {
    $query = $this->database->select('node_access', 'na')
      ->fields('na', ['nid'])
      ->condition('na.nid', $nid)
      ->condition('na.realm', AccessStorage::NODE_ACCESS_REALM);

    $result = $query->execute()
      ->fetchCol();

    if (empty($result)) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Rebuild node access for a single node.
   *
   * @param int $nid
   *   The node id for which node access records are to be recalculated.
   */
  public static function rebuildNodeAccessOne($nid) {
    // Delete existing grants for this node only.
    \Drupal::database()
      ->delete('node_access')
      ->condition('nid', $nid)
      ->execute();
    \Drupal::entityTypeManager()->getStorage('node')->resetCache([$nid]);
    $node = Node::load($nid);
    // To preserve database integrity, only write grants if the node
    // loads successfully.
    if (!empty($node)) {
      $grants = \Drupal::entityTypeManager()
        ->getAccessControlHandler('node')
        ->acquireGrants($node);
      \Drupal::service('node.grant_storage')->write($node, $grants);
    }

    return 'Processed node ' . $nid;
  }

  public function rebuildAccess($termsChanged = []): void {
    $nids = $this->getNidsForAccessRebuild($termsChanged);

    if (count($nids) > 50) {
      $operations = array_map(function($id) {
        return ['Drupal\permissions_by_term\Service\NodeAccess::rebuildNodeAccessOne', [$id]];
      }, $nids);
      $batch = [
        'title' => t('Updating content access permissions'),
        'operations' => $operations,
        'finished' => 'Drupal\permissions_by_term\Service\NodeAccess::rebuildComplete',
      ];
      batch_set($batch);

      batch_process($this->currentPath->getPath());
    }
    else {
      // Try to allocate enough time to rebuild node grants.
      Environment::setTimeLimit(240);

      // Rebuild newest nodes first so that recent content becomes available
      // quickly.
      rsort($nids);

      foreach ($nids as $nid) {
        $this->rebuildNodeAccessOne($nid);
      }
    }
  }

  /**
   * Rebuild is finished.
   */
  public static function rebuildComplete() {
    /**
     * @var \Drupal\permissions_by_term\Cache\CacheInvalidator $cacheInvalidator
     */
    $cacheInvalidator = \Drupal::service('permissions_by_term.cache_invalidator');
    $cacheInvalidator->invalidate();
  }

}
