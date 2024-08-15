<?php

namespace Drupal\commerce_wishlist\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Deletes queued wishlist items.
 *
 * @QueueWorker(
 *   id = "commerce_wishlist_item_delete",
 *   title = @Translation("Wishlist item delete"),
 *   cron = {"time" = 10}
 * )
 */
class WishlistItemDelete extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    $storage = $this->entityTypeManager->getStorage('commerce_wishlist_item');
    $wishlist_items = $storage->loadMultiple($data['ids']);
    if ($wishlist_items) {
      $storage->delete($wishlist_items);
    }
  }

}
