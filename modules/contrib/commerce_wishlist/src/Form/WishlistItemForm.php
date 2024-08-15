<?php

namespace Drupal\commerce_wishlist\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\entity\Form\EntityDuplicateFormTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the wishlist item add/edit form.
 */
class WishlistItemForm extends ContentEntityForm {

  use EntityDuplicateFormTrait;

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->entityFieldManager = $container->get('entity_field.manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityFromRouteMatch(RouteMatchInterface $route_match, $entity_type_id) {
    if ($route_match->getRawParameter('commerce_wishlist_item') !== NULL) {
      $entity = $route_match->getParameter('commerce_wishlist_item');
    }
    else {
      /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist */
      $wishlist = $route_match->getParameter('commerce_wishlist');

      // Set parent wishlist id.
      $values = [
        'wishlist_id' => $wishlist->id(),
        'type' => 'commerce_product_variation',
      ];

      $entity = $this->entityTypeManager->getStorage('commerce_wishlist_item')->create($values);
    }

    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $save = $this->entity->save();
    $this->messenger()->addStatus($this->t('The item %label has been successfully saved.', ['%label' => $this->entity->label()]));
    $form_state->setRedirect('entity.commerce_wishlist_item.collection', ['commerce_wishlist' => $this->entity->getWishlistId()]);
    return $save;
  }

}
