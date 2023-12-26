<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_pricing_item",
 *   title = @Translation("Pricing item"),
 *   description = @Translation("Pricing item"),
 *   icon = "fa fa-dollar",
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds"
 * )
 */
class PricingItemShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $text = isset($attrs['description']) ? '<li ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' . $attrs['description'] . '</li>' : '';
    return $text; 
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['description'] = array(
      '#title' => t('Description'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['description']) ? $attrs['description'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}