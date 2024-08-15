<?php

namespace Drupal\commerce_wishlist\Controller;

use Drupal\commerce_wishlist\Entity\WishlistInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the wishlist item pages.
 */
class WishlistItemController implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = new static();
    $instance->currentUser = $container->get('current_user');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->formBuilder = $container->get('form_builder');
    $instance->routeMatch = $container->get('current_route_match');
    $instance->stringTranslation = $container->get('string_translation');
    return $instance;
  }

  /**
   * Builds the item details form.
   *
   * @return array
   *   The rendered form.
   */
  public function detailsForm() {
    $wishlist_item = $this->routeMatch->getParameter('commerce_wishlist_item');
    $form_object = $this->entityTypeManager->getFormObject('commerce_wishlist_item', 'details');
    $form_object->setEntity($wishlist_item);
    $form_state = new FormState();

    return $this->formBuilder->buildForm($form_object, $form_state);
  }

  /**
   * Provides the title callback for the wishlist items collection route.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   *
   * @return string
   *   The title.
   */
  public function collectionTitle(RouteMatchInterface $route_match) {
    $wishlist = $route_match->getParameter('commerce_wishlist');
    assert($wishlist instanceof WishlistInterface);
    return $this->t('%label items', ['%label' => $wishlist->label()]);
  }

}
