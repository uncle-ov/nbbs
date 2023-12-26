<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * The image shortcode.
 *
 * @Shortcode(
 *   id = "row",
 *   title = @Translation("Row for columns"),
 *   description = @Translation("Row bootstrap tag"),
 *   child_shortcode = "col",
 *   icon = "fa fa-th-large"
 * )
 */
class RowShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {

    $attrs['class'] = 'row';
    $text = '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) .'>' . $text . '</div>';
    return $text;

  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    /**

    ROW SETTINGS


    */
  }
}