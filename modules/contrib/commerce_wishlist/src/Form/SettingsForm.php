<?php

namespace Drupal\commerce_wishlist\Form;

use Drupal\commerce\EntityHelper;
use Drupal\commerce_wishlist\Entity\WishlistType;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the wishlist settings form.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityDisplayRepository = $container->get('entity_display.repository');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'commerce_wishlist_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['commerce_wishlist.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $config = $this->config('commerce_wishlist.settings');
    $form['wishlist'] = [
      '#type' => 'details',
      '#title' => 'Wishlist settings',
      '#open' => TRUE,
    ];
    $form['wishlist']['allow_multiple'] = [
      '#type' => 'checkbox',
      '#default_value' => $config->get('allow_multiple'),
      '#title' => $this->t('Allow multiple wishlists'),
      '#description' => $this->t('Determines whether multiple wishlists are allowed.'),
    ];

    $form['anonymous_sharing'] = [
      '#type' => 'details',
      '#title' => 'Anonymous sharing',
      '#open' => TRUE,
    ];
    $form['anonymous_sharing']['allow_anonymous_sharing'] = [
      '#type' => 'checkbox',
      '#default_value' => $config->get('allow_anonymous_sharing'),
      '#title' => $this->t('Allow anonymous sharing of wishlists'),
      '#description' => $this->t('Determines whether anonymous wishlists can be shared.'),
    ];
    $form['anonymous_sharing']['duplicate'] = [
      '#type' => 'checkbox',
      '#default_value' => $config->get('duplicate'),
      '#title' => $this->t('Duplicate anonymous wishlist'),
      '#description' => $this->t('Determines if an anonymous wishlist is duplicated when shared by email.'),
      '#states' => [
        'visible' => [
          ':input[name="allow_anonymous_sharing"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $wishlist_types = WishlistType::loadMultiple();
    $options = EntityHelper::extractLabels($wishlist_types);
    $form['wishlist']['default_type'] = [
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => $config->get('default_type'),
      '#title' => $this->t('Default wishlist type'),
      '#description' => $this->t('The default wishlist type to use when creating a new wishlist.'),
    ];
    $form['view_modes'] = [
      '#type' => 'details',
      '#title' => 'View modes',
      '#description' => $this->t('The view mode to use when rendering wishlist items.'),
      '#open' => TRUE,
      '#tree' => TRUE,
    ];

    foreach (commerce_wishlist_get_purchasable_entity_types() as $entity_type_id => $entity_type) {
      $view_modes = $this->entityDisplayRepository->getViewModes($entity_type_id);
      $view_mode_labels = array_map(function ($view_mode) {
        return $view_mode['label'];
      }, $view_modes);
      $default_view_mode = $config->get('view_modes.' . $entity_type_id);
      $default_view_mode = $default_view_mode ?: 'cart';

      $form['view_modes'][$entity_type_id] = [
        '#type' => 'select',
        '#title' => $entity_type->getLabel(),
        '#options' => $view_mode_labels,
        '#default_value' => $default_view_mode,
        '#required' => TRUE,
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('commerce_wishlist.settings');
    $values = $form_state->getValues();
    foreach ([
      'allow_multiple',
      'allow_anonymous_sharing',
      'duplicate',
      'default_type',
      'view_modes',
    ] as $key) {
      if (!isset($values[$key])) {
        continue;
      }
      $config->set($key, $values[$key]);
    }
    $config->save();
  }

}
