<?php

namespace Drupal\Tests\permissions_by_term\Kernel;

use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\permissions_by_term\Service\AccessStorage;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;

/**
 * Class AccessCheckTest
 *
 * @package Drupal\Tests\permissions_by_term\Kernel
 * @group permissions_by_term
 */
class AccessCheckTest extends PBTKernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->setUpCurrentUser();
  }

  public function testDisabledRequireAllTermsGranted(): void {
    $database = $this->container->get('database');
    $database->truncate('node_access')->execute();
    $this->createRelationOneGrantedTerm();
    $this->createRelationAllGrantedTerms();

    \Drupal::configFactory()->getEditable('permissions_by_term.settings')->set('require_all_terms_granted', FALSE)->save();
    $this->assertTrue($this->accessCheck->canUserAccessByNode(Node::load($this->getNidOneGrantedTerm())));

    node_access_rebuild();

    $gids = $this->accessStorage->getGids(\Drupal::service('current_user'));

    $nodeAccess = $database->select('node_access', 'na')
      ->fields('na', ['nid'])
      ->condition('na.gid', $gids['permissions_by_term'], 'IN')
      ->condition('na.realm', AccessStorage::NODE_ACCESS_REALM);
    $permittedNids = $nodeAccess
      ->execute()
      ->fetchCol();

    $this->assertCount(2, $permittedNids);
  }

  public function testNoGrantedTermRestriction(): void {
    $database = $this->container->get('database');
    $database->truncate('node_access')->execute();
    $this->createRelationNoGrantedTerm();

    \Drupal::configFactory()->getEditable('permissions_by_term.settings')->set('require_all_terms_granted', FALSE)->save();
    $this->assertFalse($this->accessCheck->canUserAccessByNode(Node::load($this->getNidNoGrantedTerm())));

    node_access_rebuild();

    $gids = $this->accessStorage->getGids(\Drupal::service('current_user'));
    self::assertNull($gids);
  }

  public function testNoTermRestriction(): void {
    $database = $this->container->get('database');
    $database->truncate('node_access')->execute();
    $this->createRelationWithoutRestriction();

    \Drupal::configFactory()->getEditable('permissions_by_term.settings')->set('require_all_terms_granted', FALSE)->save();
    $this->assertTrue($this->accessCheck->canUserAccessByNode(Node::load($this->getNidNoRestriction())));

    node_access_rebuild();

    $gids = $this->accessStorage->getGids(\Drupal::service('current_user'));
    self::assertNull($gids);
  }

  public function testRequireAllTermsGrantedWithRestrictedTerms(): void {
    $database = $this->container->get('database');
    $database->truncate('node_access')->execute();
    $this->createRelationOneGrantedTerm();
    $this->createRelationAllGrantedTerms();

    \Drupal::configFactory()->getEditable('permissions_by_term.settings')->set('require_all_terms_granted', TRUE)->save();
    $this->assertFalse($this->accessCheck->canUserAccessByNode(Node::load($this->getNidOneGrantedTerm())));

    node_access_rebuild();

    $gids = $this->accessStorage->getGids(\Drupal::service('current_user'));

    $nodeAccess = $database->select('node_access', 'na')
      ->fields('na', ['nid'])
      ->condition('na.gid', $gids['permissions_by_term'], 'IN')
      ->condition('na.realm', AccessStorage::NODE_ACCESS_REALM);
    $permittedNids = $nodeAccess
      ->execute()
      ->fetchCol();

    $this->assertCount(1, $permittedNids);
  }

  public function testRequireAllTermsGrantedWithNoRestrictedTerms(): void {
    $database = $this->container->get('database');
    $database->truncate('node_access')->execute();
    $this->createRelationWithoutRestriction();

    \Drupal::configFactory()->getEditable('permissions_by_term.settings')->set('require_all_terms_granted', TRUE)->save();
    $this->assertFalse($this->accessCheck->canUserAccessByNode(Node::load($this->getNidNoRestriction())));

    node_access_rebuild();

    $gids = $this->accessStorage->getGids(\Drupal::service('current_user'));
    self::assertNull($gids);
  }

  public function testCheckAccessAsGuestWithNoTermRestriction(): void {
    $term = Term::create([
      'name' => 'term1',
      'vid' => 'test',
    ]);
    $term->save();

    self::assertTrue($this->accessCheck->isAccessAllowedByDatabase($term->id(), 0));
  }

  public function testCheckAccessAsGuestWithTermRestriction(): void {
    $termRestricted = Term::create([
      'name' => 'term1',
      'vid' => 'test',
    ]);
    $termRestricted->save();

    $termNotRestricted = Term::create([
      'name' => 'term1',
      'vid' => 'test',
    ]);
    $termNotRestricted->save();

    $this->accessStorage->addTermPermissionsByUserIds([1], $termRestricted->id());

    self::assertFalse($this->accessCheck->isAccessAllowedByDatabase($termRestricted->id(), 0));

    $node = Node::create([
      'type' => 'page',
      'title' => 'test_title',
      'field_tags' => [
        [
          'target_id' => $termRestricted->id()
        ],
        [
          'target_id' => $termNotRestricted->id()
        ],
      ]
    ]);
    $node->save();

    self::assertFalse($this->accessCheck->canUserAccessByNode($node, 0));
  }

  public function testBypassNodeAccess(): void {
    Vocabulary::create([
      'name'     => 'Test Multilingual',
      'vid'      => 'test_multilingual',
      'langcode' => 'de',
    ])->save();

    $term = Term::create([
      'name'     => 'term1',
      'vid'      => 'test',
      'langcode' => 'de',
    ]);
    $term->save();

    $node = Node::create([
      'type' => 'page',
      'title' => 'test_title',
      'field_tags' => [
        [
          'target_id' => $term->id()
        ],
      ]
    ]);
    $node->save();

    $this->accessStorage->addTermPermissionsByUserIds([99], $term->id(), 'de');
    $this->assertFalse($this->accessCheck->canUserAccessByNode($node, \Drupal::currentUser()->id(), 'de'));

    $editorRole = Role::create([
      'id' => 'editor',
      'label' => $this->randomMachineName(),
    ]);
    $editorRole->grantPermission('bypass node access');
    $editorRole->save();

    $user = User::load(\Drupal::currentUser()->id());

    $user->addRole('editor');
    $user->save();

    $accountSwitcher = \Drupal::service('account_switcher');
    $accountSwitcher->switchTo($user);

    $this->assertTrue($this->accessCheck->canUserAccessByNode($node, \Drupal::currentUser()->id(), 'de'));
  }

  public function testIsAnyTaxonomyTermFieldDefinedInNodeType(): void {
    self::assertTrue($this->accessCheck->isAnyTaxonomyTermFieldDefinedInNodeType('page'));
    $this->createNoTaxonomyTermRelationNodeType();
    self::assertFalse($this->accessCheck->isAnyTaxonomyTermFieldDefinedInNodeType('no_taxonomy_term_relation'));
  }

  private function createNoTaxonomyTermRelationNodeType() {
    NodeType::create([
      'type' => 'no_taxonomy_term_relation',
    ])->save();
  }

}
