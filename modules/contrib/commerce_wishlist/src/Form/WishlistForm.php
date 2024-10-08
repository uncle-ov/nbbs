<?php

namespace Drupal\commerce_wishlist\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity\Form\EntityDuplicateFormTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the wishlist add/edit form.
 */
class WishlistForm extends ContentEntityForm {

  use EntityDuplicateFormTrait;

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The wishlist provider.
   *
   * @var \Drupal\commerce_wishlist\WishlistProviderInterface
   */
  protected $wishlistProvider;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->wishlistProvider = $container->get('commerce_wishlist.wishlist_provider');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\commerce_wishlist\Entity\Wishlist $wishlist */
    $wishlist = $this->entity;
    $form = parent::form($form, $form_state);

    $form['#tree'] = TRUE;
    // Changed must be sent to the client, for later overwrite error checking.
    $form['changed'] = [
      '#type' => 'hidden',
      '#default_value' => $wishlist->getChangedTime(),
    ];

    $last_saved = $this->dateFormatter->format($wishlist->getChangedTime(), 'short');
    $form['advanced'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['entity-meta']],
      '#weight' => 99,
    ];
    $form['meta'] = [
      '#attributes' => ['class' => ['entity-meta__header']],
      '#type' => 'container',
      '#group' => 'advanced',
      '#weight' => -100,
      'date' => NULL,
      'changed' => $this->fieldAsReadOnly($this->t('Last saved'), $last_saved),
    ];
    $form['customer'] = [
      '#type' => 'details',
      '#title' => $this->t('Customer information'),
      '#group' => 'advanced',
      '#open' => TRUE,
      '#attributes' => [
        'class' => ['wishlist-form-author'],
      ],
      '#weight' => 91,
    ];

    // Move uid/mail widgets to the sidebar, or provide read-only alternatives.
    if (isset($form['uid'])) {
      $form['uid']['#group'] = 'customer';
    }
    else {
      $user_link = $wishlist->getOwner()->toLink()->toString();
      $form['customer']['uid'] = $this->fieldAsReadOnly($this->t('Customer'), $user_link);
    }

    return $form;
  }

  /**
   * Builds a read-only form element for a field.
   *
   * @param string $label
   *   The element label.
   * @param string $value
   *   The element value.
   *
   * @return array
   *   The form element.
   */
  protected function fieldAsReadOnly($label, $value) {
    return [
      '#type' => 'item',
      '#wrapper_attributes' => [
        'class' => [
          Html::cleanCssIdentifier(strtolower($label)), 'container-inline',
        ],
      ],
      '#markup' => '<h4 class="label inline">' . $label . '</h4> ' . $value,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $allow_multiple = (bool) $this->configFactory()->get('commerce_wishlist.settings')->get('allow_multiple');
    // If we don't allow multiple wishlists per customer.
    if (!$allow_multiple) {
      $uid = $form_state->getValue(['uid', '0', 'target_id']);
      // If there is not uid key, there is no ability to change owner on
      // existing. But could be added a new wishlist on existing user.
      if (!empty($uid)) {
        $account = $this->entityTypeManager->getStorage('user')->load($uid);
      }
      else {
        $account = $this->currentUser();
      }
      if ($wishlist_id = $this->wishlistProvider->getWishlistId($this->entity->bundle(), $account)) {
        $form_state->setErrorByName('duplicate', 'Cannot create a new wishlist (Only a single wishlist per customer is allowed).');
      }
    }
    return parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);

    if ($this->entity->isNew()) {
      $actions['submit_continue'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save and add items'),
        '#continue' => TRUE,
        '#submit' => ['::submitForm', '::save'],
      ];
    }

    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $save = $this->entity->save();
    $this->messenger()->addStatus($this->t('The wishlist %label has been successfully saved.', ['%label' => $this->entity->label()]));
    if (!empty($form_state->getTriggeringElement()['#continue'])) {
      $form_state->setRedirect('entity.commerce_wishlist_item.collection', ['commerce_wishlist' => $this->entity->id()]);
    }
    else {
      $form_state->setRedirect('entity.commerce_wishlist.collection');
    }
    return $save;
  }

}
