<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_chartpie",
 *   title = @Translation("Chart Pie"),
 *   description = @Translation("Chart Pie"),
 *   child_shortcode = "nd_chartpieitem",
 *   icon = "fa fa-pie-chart"
 * )
 */
class ChartPieShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['id'] = 'chart-' . time() . '-' . rand(0, 10000);
    $options = [];
    if(isset($attrs['donut']) && $attrs['donut']) {
      $options []= '"donut": "true"';
      $options []= '"donut_inner_ration": "0.4"';
    }
    $attrs['class'] = 'col-md-8 col-md-pull-2 pie-svg';
    $output = '<div class = "row"><div class = "col-md-4"><ul class = "pie-chart" data-pie-id="' . $attrs['id'] . '" data-options=\'{' . implode(', ', $options). '}\'>
      ' . $text. '
      </ul>
      </div>
      <div ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . '></div>
    </div>';
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['stroke_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Stroke Color'),
      '#default_value' => isset($attrs['stroke_color']) ? $attrs['stroke_color'] : '',
      '#prefix' => '<div class = "row"><div class = "col-xs-4">',
      '#attributes' => array('class' => array('form-control colorpicker-enable'))
    );
    $form['donut'] = array(
      '#type' => 'checkbox',
      '#title' => t('Donut'),
      '#default_value' => isset($attrs['donut']) ? $attrs['donut'] : false,
      '#prefix' => '</div><div class = "col-xs-4">',
      '#suffix' => '</div></div>'
    );
    return $form;
  }
}