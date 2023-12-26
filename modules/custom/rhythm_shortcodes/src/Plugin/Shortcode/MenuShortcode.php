<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;
use Drupal\search\Form\SearchBlockForm;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Render\RendererInterface;

// Cart
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\commerce_cart\CartProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * @Shortcode(
 *   id = "nd_menu",
 *   title = @Translation("Menu"),
 *   description = @Translation("Render menu"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-bars"
 * )
 */
class MenuShortcode extends ShortcodeBase implements ContainerFactoryPluginInterface
{
  /**
   * The cart provider.
   *
   * @var \Drupal\commerce_cart\CartProviderInterface
   */
  protected $cartProvider;
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new MenuShortcode.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\commerce_cart\CartProviderInterface $cart_provider
   *   The cart provider.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RendererInterface $renderer, $cart_provider = NULL, EntityTypeManagerInterface $entity_type_manager = NULL)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $renderer);

    $this->cartProvider = $cart_provider;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('renderer'),
      \Drupal::moduleHandler()->moduleExists('commerce') ? $container->get('commerce_cart.cart_provider') : [],
      $container->get('entity_type.manager')
    );
  }

  /**
   * @return int
   */
  protected function getCartCount()
  {

    /* @var CurrentStoreInterface $cs */
    $cs = \Drupal::service('commerce_store.current_store');
    /* @var CartProviderInterface $cpi */
    $cpi = \Drupal::service('commerce_cart.cart_provider');
    $cart = $cpi->getCart('default', $cs->getStore());
    if (!$cart) {
      return 0;
    }
    $count = 0;
    foreach ($cart->order_items as $key => $item) {
      $order_item = \Drupal\commerce_order\Entity\OrderItem::load($item->target_id);
      $count += $order_item->quantity->value;
    }
    return $count;
  }

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED)
  {
    $color = isset($attrs['type']) ? $attrs['type'] : '';
    $transparent = isset($attrs['transparent']) ? $attrs['transparent'] : FALSE;
    $color .= $transparent ? ' transparent stick-fixed' : ' js-stick';
    // Search block
    $search = isset($attrs['search']) ? $attrs['search'] : TRUE;
    if ($search && \Drupal::moduleHandler()->moduleExists('search')) {
      $form = \Drupal::formBuilder()->getForm(SearchBlockForm::class);
      $search = \Drupal::service('renderer')->render($form);
    }

    // Cart.
    $cart = isset($attrs['cart']) && $attrs['cart'] ? $attrs['cart'] : FALSE;
    $cart_count = 0;
    if (\Drupal::moduleHandler()->moduleExists('commerce') && isset($attrs['cart']) && $attrs['cart']) {
      // Get Cart block. Limited in opportunities.
      $cart_count = $this->getCartCount();
    }

    // Render Menu.
    $menu_type = isset($attrs['menu_type']) ? $attrs['menu_type'] : '';
    $menu = isset($attrs['menu']) ? $attrs['menu'] : 'main';
    $class = $menu_type == 'popup_menu' ? 'fm' : 'mn';
    if ($menu_type != 'popup_menu' && \Drupal::moduleHandler()->moduleExists('tb_megamenu')) {
      $menu_array = array(
        '#theme' => 'tb_megamenu',
        '#menu_name' => $menu,
        '#post_render' => array('\Drupal\tb_megamenu\Controller\TBMegaMenuController::tb_megamenu_attach_number_columns')
      );
      $menu = render($menu_array);
    } else {
      $menu = render_menu($menu, $class);
    }

    // Render Language
    $language = isset($attrs['language']) ? $attrs['language'] : FALSE;
    $lang_code = '';
    if ($language && \Drupal::moduleHandler()->moduleExists('language')) {
      $block = \Drupal::entityTypeManager()->getStorage('block')->load('languageswitcher')->getPlugin()->build();
      $language = \Drupal::service('renderer')->render($block);
      $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    }
    $file = isset($attrs['fid']) && !empty($attrs['fid']) ? File::load($attrs['fid']) : '';
    $logo = isset($file->uri) ? \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri()) : theme_get_setting('logo.url');

    $config = \Drupal::config('system.site');
    $site_name = $config->get('name');

    switch ($menu_type) {
      case 'popup_menu':
        $output = [
          '#theme' => 'rhythm_cms_menu_popup',
          '#menu' => $menu,
          '#logo' => $logo
        ];
        break;

      default:
        $output = [
          '#theme' => 'rhythm_cms_menu',
          '#menu' => $menu,
          '#logo' => $logo,
          '#color' => $color,
          '#search' => $search,
          '#cart' => $cart,
          '#cart_count' => $cart_count,
          '#language' => $language,
          '#lang_code' => $lang_code,
          '#site_name' => $site_name
        ];
    }

    $output = $this->render($output);
    $attrs_output = _rhythm_shortcodes_shortcode_attributes($attrs);
    if ($attrs_output) {
      $output = '<div ' . $attrs_output . '>' . $output . '</div>';
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED)
  {
    $menu_type = array('' => t('Default'), 'popup_menu' => t('Popup Menu'));
    $form['menu_type'] = array(
      '#type' => 'select',
      '#title' => t('Menu Type'),
      '#default_value' => isset($attrs['menu_type']) ? $attrs['menu_type'] : '',
      '#options' => $menu_type,
      '#attributes' => array('class' => array('form-control menu-select')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
      '#suffix' => '</div>'
    );
    $menus = array_map(function ($menu) {
      return $menu->label();
    }, \Drupal\system\Entity\Menu::loadMultiple());

    $form['menu'] = array(
      '#type' => 'select',
      '#title' => t('Menu'),
      '#default_value' => isset($attrs['menu']) ? $attrs['menu'] : '',
      '#options' => $menus,
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "col-sm-4">',
      '#suffix' => '</div></div>'
    );

    $form['fid'] = array(
      '#type' => 'textfield',
      '#title' => t('Image'),
      '#default_value' => isset($attrs['fid']) ? $attrs['fid'] : '',
      '#prefix' => '<div class = "row"><div class = "col-sm-6"><div class="image-gallery-upload ">',
      '#suffix' => '</div></div></div>',
      '#attributes' => array('class' => array('image-gallery-upload hidden')),
      '#field_suffix' => '<div class = "preview-image"></div><a href = "#" class = "vc-gallery-images-select button">' . t('Upload Image') . '</a><a href = "#" class = "gallery-remove button">' . t('Remove Image') . '</a>'
    );

    if (isset($attrs['fid']) && !empty($attrs['fid'])) {
      $file = isset($attrs['fid']) && !empty($attrs['fid']) ? File::load($attrs['fid']) : '';
      if ($file) {
        $filename = $file->getFileUri();
        $filename = ImageStyle::load('medium')->buildUrl($filename);
        $form['fid']['#prefix'] = '<div class = "row"><div class = "col-sm-6"><div class="image-gallery-upload has_image">';
        $form['fid']['#field_suffix'] = '<div class = "preview-image"><img src="' . $filename . '"></div><a href = "#" class = "vc-gallery-images-select button">' . t('Upload Image') . '</a><a href = "#" class = "gallery-remove button">' . t('Remove Image') . '</a>';
      }
    }

    $states = array(
      'visible' => array(
        '.menu-select' => array('!value' => 'popup_menu'),
      ),
    );
    $types = array('white' => t('White'), 'dark' => t('Dark'));
    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Background Type'),
      '#options' => $types,
      '#default_value' => isset($attrs['type']) ? $attrs['type'] : 'white',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
      '#states' => $states,
    );
    $form['transparent'] = array(
      '#type' => 'checkbox',
      '#title' => t('Transparent'),
      '#default_value' => isset($attrs['transparent']) ? $attrs['transparent'] : TRUE,
      '#prefix' => '</div><div class = "col-sm-4">',
      '#suffix' => '</div></div>',
      '#states' => $states,
    );
    $form['search'] = array(
      '#type' => 'checkbox',
      '#title' => t('Search Box'),
      '#default_value' => isset($attrs['search']) ? $attrs['search'] : TRUE,
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
      '#states' => $states,
    );
    $form['cart'] = array(
      '#type' => 'checkbox',
      '#title' => t('Cart'),
      '#default_value' => isset($attrs['cart']) ? $attrs['cart'] : FALSE,
      '#prefix' => '</div><div class = "col-sm-3">',
      '#states' => $states,
    );
    $form['language'] = array(
      '#type' => 'checkbox',
      '#title' => t('Language Swticher'),
      '#default_value' => isset($attrs['language']) ? $attrs['language'] : FALSE,
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
      '#states' => $states,
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function description($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED)
  {
    $value = '';
    if (isset($attrs['admin_url']) && strpos($attrs['admin_url'], 'admin/structure/menu') !== FALSE) {
      $form = $this->settings($attrs, $text);
      $link_text = $form['admin_url']['#options'][$attrs['admin_url']];
      $link_url = Url::fromUri('internal:/' . $attrs['admin_url'], ['attributes' => ['target' => '_blank']]);
      $link = Link::fromTextAndUrl($link_text, $link_url)->toString();
      $value = $link->getGeneratedLink();
    }
    return $value;
  }
}