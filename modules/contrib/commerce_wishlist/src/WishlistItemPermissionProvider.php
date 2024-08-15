<?php

namespace Drupal\commerce_wishlist;

use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\entity\EntityPermissionProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides permissions for wishlist items.
 */
class WishlistItemPermissionProvider implements EntityPermissionProviderInterface, EntityHandlerInterface {

  use StringTranslationTrait;

  /**
   * The entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    $instance = new static();
    $instance->entityTypeBundleInfo = $container->get('entity_type.bundle.info');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildPermissions(EntityTypeInterface $entity_type) {
    $entity_type_id = $entity_type->id();
    $bundles = $this->entityTypeBundleInfo->getBundleInfo($entity_type_id);
    $permissions = [];
    foreach ($bundles as $bundle_name => $bundle_info) {
      $permissions["manage {$bundle_name} {$entity_type_id}"] = [
        'title' => $this->t('[Wishlist items] Manage %bundle', [
          '%bundle' => $bundle_info['label'],
        ]),
        'provider' => 'commerce_wishlist',
      ];
    }

    return $permissions;
  }

}
