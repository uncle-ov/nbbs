<?php

namespace Drupal\permissions_by_term\Service;

use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityFieldManager;
use Drupal\permissions_by_term\Event\PermissionsByTermDeniedEvent;
use Drupal\taxonomy\Entity\Term;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;


/**
 * AccessCheckService class.
 */
class AccessCheck {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * @var ContainerAwareEventDispatcher
   */
  private $eventDispatcher;

  /**
   * @var EntityFieldManager
   */
  private $entityFieldManager;

  /**
   * Constructs AccessCheck object.
   *
   * @param Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database, ContainerAwareEventDispatcher $eventDispatcher, EntityFieldManager $entityFieldFieldManager) {
    $this->database  = $database;
    $this->eventDispatcher = $eventDispatcher;
    $this->entityFieldManager = $entityFieldFieldManager;
  }

  public function canUserAccessByNode(Node $node, $uid = FALSE, $langcode = ''): bool {
    if (!$this->isAnyTaxonomyTermFieldDefinedInNodeType($node->getType())) {
      return TRUE;
    }

    $langcode = ($langcode === '') ? \Drupal::languageManager()->getCurrentLanguage()->getId() : $langcode;

    if (empty($uid)) {
      $uid = \Drupal::currentUser()->id();
    }

    $user = User::load($uid);

    if ($user instanceof User && $user->hasPermission('view own unpublished content') &&
      (int) $node->getOwnerId() === (int) $uid &&
      !$node->isPublished()
    ) {
      return TRUE;
    }

    if ($user instanceof User && $user->hasPermission('view any unpublished content') &&
      !$node->isPublished()
    ) {
      return TRUE;
    }

    if ($user instanceof User && $user->hasPermission('bypass node access')) {
      return TRUE;
    }

    if ($user->id() === '0' && !$user->hasPermission('view any unpublished content') &&
      !$node->isPublished()
    ) {
      return FALSE;
    }

    if ((int) $user->id() !== (int) $node->getOwnerId() && !$node->isPublished()) {
      return FALSE;
    }

    $configPermissionMode = \Drupal::config('permissions_by_term.settings')->get('permission_mode');
    $requireAllTermsGranted = \Drupal::config('permissions_by_term.settings')->get('require_all_terms_granted');

    if (!$configPermissionMode && !$requireAllTermsGranted) {
      $access_allowed = TRUE;
    } else {
      $access_allowed = FALSE;
    }

    $terms = $this->database
      ->select('taxonomy_index', 'ti')
      ->fields('ti', ['tid'])
      ->condition('nid', $node->id());

    // Query should get only selected target bundles.
    $target_bundles = \Drupal::config('permissions_by_term.settings')->get('target_bundles');
    if (!empty($target_bundles)) {
      $terms->innerJoin('taxonomy_term_data', 'ttd', 'ti.tid=ttd.tid');
      $terms->condition('vid', $target_bundles, 'IN');
    }

    $terms = $terms->execute()->fetchAll();

    if (empty($terms) && !$configPermissionMode) {
      return TRUE;
    }

    foreach ($terms as $term) {
      $termInfo = Term::load($term->tid);

      if ($termInfo instanceof Term && $termInfo->get('langcode')->getLangcode() == $langcode) {
        if (!$this->isAnyPermissionSetForTerm($term->tid, $termInfo->get('langcode')->getLangcode())) {
          continue;
        }
        $access_allowed = $this->isAccessAllowedByDatabase($term->tid, $uid, $termInfo->get('langcode')->getLangcode());
        if (!$access_allowed && $requireAllTermsGranted) {
          return $access_allowed;
        }

        if ($access_allowed && !$requireAllTermsGranted) {
          return $access_allowed;
        }
      }

    }

    return $access_allowed;
  }

  /**
   * @param int      $tid
   * @param bool|int $uid
   * @param string   $langcode
   * @return bool
   */
  public function isAccessAllowedByDatabase($tid, $uid = FALSE, $langcode = '') {
		$langcode = ($langcode === '') ? \Drupal::languageManager()->getCurrentLanguage()->getId() : $langcode;

    if (is_numeric($uid) && $uid >= 0) {
      $user = User::load($uid);
    } else {
      $user = \Drupal::currentUser();
    }

    $tid = (int) $tid;

    if (!$this->isAnyPermissionSetForTerm($tid, $langcode) && !\Drupal::config('permissions_by_term.settings')->get('permission_mode')) {
      return TRUE;
    }

    /* At this point permissions are enabled, check to see if this user or one
     * of their roles is allowed.
     */
    foreach ($user->getRoles() as $sUserRole) {

      if ($this->isTermAllowedByUserRole($tid, $sUserRole, $langcode)) {
        return TRUE;
      }

    }

    if ($this->isTermAllowedByUserId($tid, $user->id(), $langcode)) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param int    $tid
   * @param int    $iUid
   * @param string $langcode
   *
   * @return bool
   */
  private function isTermAllowedByUserId($tid, $iUid, $langcode) {
    $query_result = $this->database->query("SELECT uid FROM {permissions_by_term_user} WHERE tid = :tid AND uid = :uid AND langcode = :langcode",
      [':tid' => $tid, ':uid' => $iUid, ':langcode' => $langcode])->fetchField();

    if (!empty($query_result)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * @param int    $tid
   * @param string $sUserRole
   * @param string $langcode
   *
   * @return bool
   */
  public function isTermAllowedByUserRole($tid, $sUserRole, $langcode) {
    $query_result = $this->database->query("SELECT rid FROM {permissions_by_term_role} WHERE tid = :tid AND rid IN (:user_roles) AND langcode = :langcode",
      [':tid' => $tid, ':user_roles' => $sUserRole, ':langcode' => $langcode])->fetchField();

    if (!empty($query_result)) {
      return TRUE;
    }
    else {
      return FALSE;
    }

  }

  /**
   * @param int    $tid
   * @param string $langcode
   *
   * @return bool
   */
  public function isAnyPermissionSetForTerm($tid, $langcode = ''): bool {
		$langcode = ($langcode === '') ? \Drupal::languageManager()->getCurrentLanguage()->getId() : $langcode;

    $iUserTableResults = (int)$this->database->query("SELECT COUNT(1) FROM {permissions_by_term_user} WHERE tid = :tid AND langcode = :langcode",
      [':tid' => $tid, ':langcode' => $langcode])->fetchField();

    $iRoleTableResults = (int)$this->database->query("SELECT COUNT(1) FROM {permissions_by_term_role} WHERE tid = :tid AND langcode = :langcode",
      [':tid' => $tid, ':langcode' => $langcode])->fetchField();

    if ($iUserTableResults > 0 ||
      $iRoleTableResults > 0) {
      return TRUE;
    }

    return FALSE;
  }

  public function handleNode(Node $node, string $langcode): AccessResult {
    $result = AccessResult::neutral();

    if (!$this->canUserAccessByNode($node, false, $langcode)) {
      $this->dispatchDeniedEvent($node->id());

      $result = AccessResult::forbidden();
    }

    return $result;
  }

  public function dispatchDeniedEventOnRestricedAccess(Node $node, string $langcode): void {
    if (!$this->canUserAccessByNode($node, false, $langcode)) {
      $this->dispatchDeniedEvent($node->id());
    }
  }

  private function dispatchDeniedEvent($nodeId): void {
    $accessDeniedEvent = new PermissionsByTermDeniedEvent($nodeId);
    $this->eventDispatcher->dispatch($accessDeniedEvent, PermissionsByTermDeniedEvent::NAME);
  }

  public function isAnyTaxonomyTermFieldDefinedInNodeType(string $nodeType) {
    $fieldDefinitons = $this->entityFieldManager->getFieldDefinitions('node', $nodeType);
    foreach ($fieldDefinitons as $fieldDefiniton) {
      if ($fieldDefiniton->getType() === 'entity_reference' && is_numeric(strpos($fieldDefiniton->getSetting('handler'), 'taxonomy_term'))) {
        return TRUE;
      }
    }

    return FALSE;
  }

}
