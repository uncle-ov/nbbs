<?php

namespace Drupal\rhythm_cms\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;

/**
 * Plugin implementation of the 'image slider' formatter.
 *
 * @FieldFormatter(
 *   id = "rhythm_cms_images_one_main",
 *   label = @Translation("Rhythm CMS: Images One Main"),
 *   field_types = {
 *     "entity_reference",
 *     "image",
 *   }
 * )
 */
class NDRhythmImagesOneMainFormatter extends ImageFormatter implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The image style entity storage.
   *
   * @var \Drupal\image\ImageStyleStorageInterface
   */
  protected $imageStyleStorage;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'image_style' => '',
      'image_style_small' => '',
      'col' => '',
      'zoom' => TRUE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $image_styles = image_style_options(FALSE);
    $description_link = Link::fromTextAndUrl(
      $this->t('Configure Image Styles'),
      Url::fromRoute('entity.image_style.collection')
    );
    $element['image_style'] = [
      '#title' => t('Image Style for Main Image'),
      '#type' => 'select',
      '#default_value' => $this->getSetting('image_style'),
      '#empty_option' => t('None (original image)'),
      '#options' => $image_styles,
      '#description' => $description_link->toRenderable() + [
        '#access' => $this->currentUser->hasPermission('administer image styles')
      ],
    ];
    $element['image_style_small'] = [
      '#title' => t('Image Style for Small Images'),
      '#type' => 'select',
      '#default_value' => $this->getSetting('image_style_small'),
      '#empty_option' => t('None (original image)'),
      '#options' => $image_styles,
      '#description' => $description_link->toRenderable() + [
          '#access' => $this->currentUser->hasPermission('administer image styles')
        ],
    ];
    $cols = [2, 3, 4, 6];
    $element['col'] = [
      '#type' => 'select',
      '#options' => array_combine($cols, $cols),
      '#title' => t('Columns Count for Small Images'),
      '#default_value' => $this->getSetting('col') ? $this->getSetting('col') : 3,
    ];
    $element['zoom'] = [
      '#type' => 'checkbox',
      '#title' => t('Add Zoom feature'),
      '#default_value' => $this->getSetting('zoom'),
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $image_styles = image_style_options(FALSE);
    // Unset possible 'No defined styles' option.
    unset($image_styles['']);
    // Styles could be lost because of enabled/disabled modules that defines
    // their styles in code.
    $image_style_setting = $this->getSetting('image_style');
    $image_style_small_setting = $this->getSetting('image_style_small');
    $cols = $this->getSetting('col');
    $zoom = $this->getSetting('zoom');

    $summary[] = isset($image_styles[$image_style_setting]) ? t('Image style: @style', ['@style' => $image_styles[$image_style_setting]]) : t('Image style: @style', ['@style' => 'Original Image']);
    $summary[] = isset($image_styles[$image_style_small_setting]) ? t('Image small style: @style', ['@style' => $image_styles[$image_style_small_setting]]) : t('Image small style: @style', ['@style' => 'Original Image']);
    $summary[] = !empty($cols) ? t('Columns count for small image: @cols', ['@cols' => $cols]) : t('Columns count for small image: @cols', ['@cols' => 0]);
    $summary[] = $zoom ? t('Zoom enabled') : t('Zoom disabled');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $product_variation = $items->getEntity();
    $product_variation_fields = $product_variation->getFields();
    $sale_text = isset($product_variation_fields['field_sale_text']) ? $product_variation_fields['field_sale_text']->value : '';

    $files = $this->getEntitiesToView($items, $langcode);
    // Early opt-out if the field is empty.
    if (empty($files)) {
      return $elements;
    }

    $image_style_setting = $this->getSetting('image_style');
    $image_style_small_setting = $this->getSetting('image_style_small');
    // Collect cache tags to be added for each item in the field.
    $base_cache_tags = [];
    $small_base_cache_tags = [];
    if (!empty($image_style_setting)) {
      $image_style = $this->imageStyleStorage->load($image_style_setting);
      $base_cache_tags = $image_style->getCacheTags();
    }
    if (!empty($image_style_small_setting)) {
      $image_small_style = $this->imageStyleStorage->load($image_style_small_setting);
      $small_base_cache_tags = $image_small_style->getCacheTags();
    }

    // One main Image.
    $main_image = [];
    foreach ($files as $delta => $file) {
      $cache_contexts = [];
      $cache_contexts[] = 'url.site';
      $cache_tags = Cache::mergeTags($base_cache_tags, $file->getCacheTags());
      $item = $file->_referringItem;
      $item_attributes = $item->_attributes;
      unset($item->_attributes);

      $main_image['image'] = [
        '#theme' => 'image_formatter',
        '#item' => $item,
        '#item_attributes' => $item_attributes,
        '#image_style' => $image_style_setting,
        '#cache' => [
          'tags' => $cache_tags,
          'contexts' => $cache_contexts,
        ],
      ];

      $image_uri = $file->getFileUri();
      $main_image['url'] = \Drupal::service('file_url_generator')->generateAbsoluteString($image_uri);
      // Only one iteration.
      break;
    }

    // Smalls.
    $small_images = [];
    array_shift($files);
    foreach ($files as $delta => $file) {
      $cache_contexts = [];
      $cache_contexts[] = 'url.site';
      $small_cache_tags = Cache::mergeTags($small_base_cache_tags, $file->getCacheTags());
      // Extract field item attributes for the theme function, and unset them
      // from the $item so that the field template does not re-render them.
      $item = $file->_referringItem;
      $item_attributes = $item->_attributes;
      unset($item->_attributes);

      $small_images[$delta]['image'] = [
        '#theme' => 'image_formatter',
        '#item' => $item,
        '#item_attributes' => $item_attributes,
        '#image_style' => $image_style_small_setting,
        '#cache' => [
          'tags' => $small_cache_tags,
          'contexts' => $cache_contexts,
        ],
      ];

      $image_uri = $file->getFileUri();
      $small_images[$delta]['url'] = \Drupal::service('file_url_generator')->generateAbsoluteString($image_uri);
    }

    $theme_array = [
      '#theme' => 'rhythm_cms_images_one_main_formatter',
      '#main_image' => $main_image,
      '#small_images' => $small_images,
      '#col' => $this->getSetting('col'),
      '#zoom' => $this->getSetting('zoom'),
      '#sale' => !is_null($sale_text) ? $sale_text : '',
    ];
    $elements[0]['#markup'] = \Drupal::service('renderer')->renderPlain($theme_array);

    return $elements;
  }
}
