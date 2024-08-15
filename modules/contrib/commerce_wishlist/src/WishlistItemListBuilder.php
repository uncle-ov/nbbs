<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce_price\Calculator;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the list builder for wishlist items.
 */
class WishlistItemListBuilder extends EntityListBuilder {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    $instance = parent::createInstance($container, $entity_type);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->routeMatch = $container->get('current_route_match');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEntityIds() {
    $wishlist = $this->routeMatch->getParameter('commerce_wishlist');
    $query = $this->getStorage()->getQuery()
      ->accessCheck(TRUE)
      ->condition('wishlist_id', $wishlist->id())
      ->sort('purchasable_entity')
      ->sort('quantity');
    // Only add the pager if a limit is specified.
    if ($this->limit) {
      $query->pager($this->limit);
    }
    return $query->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Id');
    $header['item'] = $this->t('Item');
    $header['quantity'] = $this->t('Quantity');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemInterface $entity */
    $row['id'] = $entity->id();
    $row['item'] = $entity->getTitle();
    $row['quantity'] = Calculator::trim($entity->getQuantity());

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = parent::render();
    $build['table']['#empty'] = $this->t('There are no wishlist items yet.');
    return $build;
  }

}
