<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_div",
 *   title = @Translation("DIV Container"),
 *   description = @Translation("DIV tag"),
 *   icon = "fa fa-folder-o"
 * )
 */
class DivShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['class'] = isset($attrs['class']) ? $attrs['class'] . ' ' : '';
    $attrs['class'] .= isset($attrs['vertical_align']) && $attrs['vertical_align'] ? 'home-text ': '';
    $text = '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) .'>' . $text . '</div>';
    if(isset($attrs['vertical_align']) && $attrs['vertical_align'] ) {
      $text = '<div class = "home-content">' . $text . '</div>';
    }
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['vertical_align'] = array(
      '#title' => t('Vertical Align'),
      '#type' => 'checkbox',
      '#default_value' => isset($attrs['vertical_align']) ? $attrs['vertical_align'] : FALSE,
    );
    return $form;
  }
}