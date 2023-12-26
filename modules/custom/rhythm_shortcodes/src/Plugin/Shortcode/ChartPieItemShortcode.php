<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_chartpieitem",
 *   title = @Translation("Chart Pie Item"),
 *   description = @Translation("Chart Pie"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-puzzle-piece"
 * )
 */
class ChartPieItemShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $color = isset($attrs['value_color']) ? ' style = "color:#' . trim($attrs['value_color'], '#') . '"' : '';
    $attrs['value'] = isset($attrs['value']) ? $attrs['value'] : '';
    $attrs['title'] = isset($attrs['title']) ? $attrs['title'] : '';
    $output = '<li data-value="' . $attrs['value'] .'"' . $color. '>' . $attrs['title'] .'</li>';
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => isset($attrs['title']) ? $attrs['title'] : '',
      '#prefix' => '<div class = "row"><div class = "col-xs-4">',
      '#attributes' => array('class' => array('form-control'))
    );
    $form['value'] = array(
      '#type' => 'textfield',
      '#title' => t('Value'),
      '#default_value' => isset($attrs['value']) ? $attrs['value'] : '',
      '#prefix' => '</div><div class = "col-xs-4">',
      '#attributes' => array('class' => array('form-control'))
    );
    $form['value_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Value Color'),
      '#default_value' => isset($attrs['value_color']) ? $attrs['value_color'] : '',
      '#prefix' => '</div><div class = "col-xs-4">',
      '#suffix' => '</div></div>',
      '#attributes' => array('class' => array('form-control colorpicker-enable'))
    );

    return $form;
  }
}