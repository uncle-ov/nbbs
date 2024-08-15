<?php

namespace Drupal\commerce_wishlist\Form;

use Drupal\commerce\AjaxFormTrait;
use Drupal\commerce\Context;
use Drupal\commerce\PurchasableEntityInterface;
use Drupal\commerce_wishlist\Entity\WishlistInterface;
use Drupal\commerce_wishlist\Entity\WishlistItemInterface;
use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the wishlist user form.
 *
 * Used for both the canonical ("/wishlist/{code}") and user-form
 * ("/user/{user}/wishlist/{commerce_wishlist}") pages.
 */
class WishlistUserForm extends EntityForm {

  use AjaxFormTrait;

  /**
   * The cart manager.
   *
   * @var \Drupal\commerce_cart\CartManagerInterface
   */
  protected $cartManager;

  /**
   * The cart provider.
   *
   * @var \Drupal\commerce_cart\CartProviderInterface
   */
  protected $cartProvider;

  /**
   * The current store.
   *
   * @var \Drupal\commerce_store\CurrentStoreInterface
   */
  protected $currentStore;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The order type resolver.
   *
   * @var \Drupal\commerce_order\Resolver\OrderTypeResolverInterface
   */
  protected $orderTypeResolver;

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The wishlist settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $settings;

  /**
   * The chain base price resolver.
   *
   * @var \Drupal\commerce_price\Resolver\ChainPriceResolverInterface
   */
  protected $chainPriceResolver;

  /**
   * The wishlist manager.
   *
   * @var \Drupal\commerce_wishlist\WishlistManagerInterface
   */
  protected $wishlistManager;

  /**
   * The wishlist session.
   *
   * @var \Drupal\commerce_wishlist\WishlistSessionInterface
   */
  protected $wishlistSession;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->settings = $container->get('config.factory')->get('commerce_wishlist.settings');
    $instance->cartManager = $container->get('commerce_cart.cart_manager');
    $instance->cartProvider = $container->get('commerce_cart.cart_provider');
    $instance->currentStore = $container->get('commerce_store.current_store');
    $instance->currentUser = $container->get('current_user');
    $instance->orderTypeResolver = $container->get('commerce_order.chain_order_type_resolver');
    $instance->routeMatch = $container->get('current_route_match');
    $instance->chainPriceResolver = $container->get('commerce_price.chain_price_resolver');
    $instance->wishlistManager = $container->get('commerce_wishlist.wishlist_manager');
    $instance->wishlistSession = $container->get('commerce_wishlist.wishlist_session');
    $instance->languageManager = $container->get('language_manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist */
    $wishlist = $this->entity;
    $owner_access = $this->ownerAccess($wishlist);
    $anonymous_sharing = $this->settings->get('allow_anonymous_sharing');
    $wishlist_has_items = $wishlist->hasItems();

    $form['#tree'] = TRUE;
    $form['#process'][] = '::processForm';
    $form['#theme'] = 'commerce_wishlist_user_form';
    $form['#attached']['library'][] = 'commerce_wishlist/user';
    // Workaround for core bug #2897377.
    $form['#id'] = Html::getId($form_state->getBuildInfo()['form_id']);

    $form['header'] = [
      '#type' => 'container',
    ];
    $form['header']['empty_text'] = [
      '#markup' => $this->t('Your wishlist is empty.'),
      '#access' => !$wishlist_has_items,
    ];
    $form['header']['add_all_to_cart'] = [
      '#type' => 'submit',
      '#value' => t('Add the entire list to cart'),
      '#ajax' => [
        'callback' => [get_called_class(), 'ajaxRefreshForm'],
      ],
      '#access' => $wishlist_has_items,
    ];
    $form['header']['share'] = [
      '#type' => 'link',
      '#title' => $this->t('Share the list by email'),
      '#url' => $wishlist->toUrl('share-form', [
        'language' => $this->languageManager->getCurrentLanguage(),
      ]),
      '#attributes' => [
        'class' => [
          'use-ajax',
          'button',
          'btn',
          'btn-default',
          'wishlist-button',
        ],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => Json::encode([
          'width' => 700,
          'title' => $this->t('Share the list by email'),
        ]),
        'role' => 'button',
      ],
      '#access' => $owner_access && $wishlist_has_items,
    ];
    if ($wishlist->getOwner()->isAnonymous() && !$anonymous_sharing) {
      $form['header']['share']['#access'] = FALSE;
    }

    $form['items'] = [];
    foreach ($wishlist->getItems() as $item) {
      $purchasable_entity = $item->getPurchasableEntity();
      if (!$purchasable_entity || !$purchasable_entity->access('view')) {
        continue;
      }
      $item_form = &$form['items'][$item->id()];

      $item_form = [
        '#type' => 'container',
      ];
      $item_form['entity'] = $this->renderPurchasableEntity($purchasable_entity);
      $item_form['details'] = [
        '#theme' => 'commerce_wishlist_item_details',
        '#wishlist_item_entity' => $item,
      ];
      $item_form['details_edit'] = [
        '#type' => 'link',
        '#title' => $this->t('Edit details'),
        '#url' => $item->toUrl('details-form'),
        '#attributes' => [
          'class' => [
            'use-ajax',
            'wishlist-item__details-edit-link',
          ],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode([
            'width' => 700,
            'title' => $this->t('Edit details'),
          ]),
        ],
        '#access' => $owner_access,
      ];
      $item_form['actions'] = [
        '#type' => 'container',
      ];
      $item_form['actions']['add_to_cart'] = [
        '#type' => 'submit',
        '#value' => t('Add to cart'),
        '#ajax' => [
          'callback' => [get_called_class(), 'ajaxRefreshForm'],
        ],
        '#submit' => [
          '::addToCartSubmit',
        ],
        '#name' => 'add-to-cart-' . $item->id(),
        '#item_id' => $item->id(),
        '#combine' => TRUE,
      ];
      $item_form['actions']['remove'] = [
        '#type' => 'submit',
        '#value' => t('Remove'),
        '#ajax' => [
          'callback' => [get_called_class(), 'ajaxRefreshForm'],
        ],
        '#submit' => [
          '::removeItem',
        ],
        '#name' => 'remove-' . $item->id(),
        '#access' => $owner_access,
        '#item_id' => $item->id(),
      ];
    }
    $form['#cache']['tags'][] = 'config:commerce_wishlist.settings';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function actionsElement(array $form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * Submit callback for the "Add to cart" button.
   */
  public function addToCartSubmit(array $form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $wishlist_item_storage = $this->entityTypeManager->getStorage('commerce_wishlist_item');
    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item */
    $wishlist_item = $wishlist_item_storage->load($triggering_element['#item_id']);
    $this->addItemToCart($wishlist_item, $triggering_element['#combine']);
  }

  /**
   * Submit callback for the "Remove" button.
   */
  public function removeItem(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist */
    $wishlist = $this->entity;
    $triggering_element = $form_state->getTriggeringElement();
    $wishlist_item_storage = $this->entityTypeManager->getStorage('commerce_wishlist_item');
    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item */
    $wishlist_item = $wishlist_item_storage->load($triggering_element['#item_id']);
    $this->wishlistManager->removeWishlistItem($wishlist, $wishlist_item);

    $this->messenger()->addStatus($this->t('@entity has been removed from your wishlist.', [
      '@entity' => $wishlist_item->label(),
    ]));
    $form_state->setRebuild(TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $complete_form = $form_state->getCompleteForm();
    /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist */
    $wishlist = $this->entity;
    foreach ($wishlist->getItems() as $wishlist_item) {
      $combine = $complete_form['items'][$wishlist_item->id()]['actions']['add_to_cart']['#combine'];
      $this->addItemToCart($wishlist_item, $combine);
    }
  }

  /**
   * Renders the given purchasable entity.
   *
   * @param \Drupal\commerce\PurchasableEntityInterface $purchasable_entity
   *   The purchasable entity.
   *
   * @return array
   *   The render array.
   */
  protected function renderPurchasableEntity(PurchasableEntityInterface $purchasable_entity) {
    $entity_type_id = $purchasable_entity->getEntityTypeId();
    $view_builder = $this->entityTypeManager->getViewBuilder($entity_type_id);
    $view_mode = $this->settings->get('view_modes.' . $entity_type_id);
    $view_mode = $view_mode ?: 'cart';
    $build = $view_builder->view($purchasable_entity, $view_mode);

    return $build;
  }

  /**
   * Checks whether the current user owns the given wishlist.
   *
   * Used to determine whether the user is allowed to modify and share
   * the wishlist.
   *
   * @param \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist
   *   The wishlist.
   *
   * @return bool
   *   TRUE if the current user owns the given wishlist, FALSE otherwise.
   */
  protected function ownerAccess(WishlistInterface $wishlist) {
    if ($this->currentUser->isAnonymous()) {
      // Anonymous wishlists aren't fully implemented yet.
      return $this->wishlistSession->hasWishlistId($wishlist->id());
    }
    if ($wishlist->getOwnerId() != $this->currentUser->id()) {
      return FALSE;
    }
    if ($this->routeMatch->getRouteName() != 'entity.commerce_wishlist.user_form') {
      // Users should only modify their wishlists via the user form.
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Adds a wishlist item to the cart.
   *
   * @param \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item
   *   The wishlist item to move to the cart.
   * @param bool $combine
   *   The combine value.
   */
  protected function addItemToCart(WishlistItemInterface $wishlist_item, $combine = TRUE) {
    $purchasable_entity = $wishlist_item->getPurchasableEntity();
    /** @var \Drupal\commerce_order\OrderItemStorageInterface $order_item_storage */
    $order_item_storage = $this->entityTypeManager->getStorage('commerce_order_item');
    $values = [
      'quantity' => $wishlist_item->getQuantity(),
    ];
    $order_item = $order_item_storage->createFromPurchasableEntity($purchasable_entity, $values);
    $order_type_id = $this->orderTypeResolver->resolve($order_item);
    $store = $this->selectStore($purchasable_entity);
    $cart = $this->cartProvider->getCart($order_type_id, $store);
    if (!$order_item->isUnitPriceOverridden()) {
      $context = new Context($this->currentUser, $store);
      $resolved_price = $this->chainPriceResolver->resolve($purchasable_entity, $order_item->getQuantity(), $context);
      $order_item->setUnitPrice($resolved_price);
    }
    if (!$cart) {
      $cart = $this->cartProvider->createCart($order_type_id, $store);
    }
    $this->cartManager->addOrderItem($cart, $order_item, $combine);
  }

  /**
   * Selects the store for the given purchasable entity.
   *
   * Copied over from AddToCartForm.
   *
   * If the entity is sold from one store, then that store is selected.
   * If the entity is sold from multiple stores, and the current store is
   * one of them, then that store is selected.
   *
   * @param \Drupal\commerce\PurchasableEntityInterface $entity
   *   The entity being added to cart.
   *
   * @throws \Exception
   *   When the entity can't be purchased from the current store.
   *
   * @return \Drupal\commerce_store\Entity\StoreInterface
   *   The selected store.
   */
  protected function selectStore(PurchasableEntityInterface $entity) {
    $stores = $entity->getStores();
    if (count($stores) === 1) {
      $store = reset($stores);
    }
    elseif (count($stores) === 0) {
      // Malformed entity.
      throw new \Exception('The given entity is not assigned to any store.');
    }
    else {
      $store = $this->currentStore->getStore();
      if (!in_array($store, $stores)) {
        // Indicates that the site listings are not filtered properly.
        throw new \Exception("The given entity can't be purchased from the current store.");
      }
    }

    return $store;
  }

}
