<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * @Shortcode(
 *   id = "nd_text_rotator",
 *   title = @Translation("Text Rotator"),
 *   description = @Translation("Text Rotator"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-sort-alpha-asc"
 * )
 */
class TextRotatorShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $text = '';
    foreach($attrs as $name => $value) {
      if(strpos($name, 'text_line_') === 0) {
        $text .= $value . ",\n";
      }
    }
    $text = trim($text, ",\n");
    $attrs['class'] = 'text-rotate' . (isset($attrs['class']) ? ' ' . $attrs['class'] : '');
    return '<span ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . '>' . $text . '</span>';
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    if(isset($form_state['values'])) {
      $attrs = $form_state['values'];
    }
    // Ajax handled Items
    $form['items'] = array(
      '#type' => 'container',
      '#attributes' => array('id' => array('nd_visualshortcodes_text_rotator')),
    );

    $attrs['text_line_1'] = isset($attrs['text_line_1']) ? $attrs['text_line_1'] : '';

    for($i = 0; $i < 10; $i++) {
      $form['items'] += $this->rhythm_shortcodes_shortcode_text_rotator_item($attrs, $i);
    }

    return $form;
  }

  function rhythm_shortcodes_shortcode_text_rotator_item($attrs, $i) {
    $form['text_line_' . $i] = array(
      '#type' => 'textfield',
      '#default_value' => isset($attrs['text_line_' . $i]) ? $attrs['text_line_' . $i] : '',
      '#attributes' => array('class' => array('form-control'))
    );
    return $form;
  }

  function rhythm_shortcodes_shortcode_list_ajax_callback($form, $form_state) {
    return $form['shortcode']['settings']['items'];
  }

}