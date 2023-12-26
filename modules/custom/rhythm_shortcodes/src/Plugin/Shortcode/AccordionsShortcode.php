<?php

/**
 * @file
 * Contains \Drupal\rhythm_cms\Plugin\Shortcode\ButtonShortcode.
 */

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * The image shortcode.
 *
 * @Shortcode(
 *   id = "nd_accordions",
 *   title = @Translation("Accordions Container"),
 *   description = @Translation("Accordions container"),
 *   child_shortcode = "nd_accordion",
 *   icon = "fa fa-bars"
 * )
 */
class AccordionsShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {

    $attrs['class'] = isset($attrs['multiple_active']) && $attrs['multiple_active'] ? 'toggle' : 'accordion';
    $text = '<dl ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . '>' . $text . '</dl>';
    return $text;

  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['multiple_active'] = array(
      '#title' => t('Multiple active'),
      '#type' => 'checkbox',
      '#default_value' => isset($attrs['multiple_active']) ? $attrs['multiple_active'] : FALSE,
    );  
    return $form;
  }
}