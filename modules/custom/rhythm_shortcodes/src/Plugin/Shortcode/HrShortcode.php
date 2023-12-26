<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_hr",
 *   title = @Translation("HR Border Line"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   description = @Translation("Hr Tag"),
 *   icon = "fa fa-ellipsis-h",
 * )
 */
class HrShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $text = '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '><hr class="mt-0 mb-0"></div>';
    return $text;
  }

}