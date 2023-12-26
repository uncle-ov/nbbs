<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_slider_item",
 *   title = @Translation("Slider item"),
 *   description = @Translation("Slider item"),
 *   icon = "fa fa-long-arrow-right"
 * )
 */
class SliderShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    return '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . '>' . $text . '</div>';
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $types = array('' => t('Simple'), 'fullwidth' => t('Fullwidth'), 'fullwidth-zoom' => t('Fullwidth Zoom Effect'), 'fullwidth-bg' => t('Fullwidth Background'), 'wide' => t('Wide Autoscroll'), 'small_wide' => t('Wide Small Autoscroll'), 'images_pager' => t('Images Pager'));
    $form['slide_anim'] = array(
      '#type' => 'select',
      '#options' => $types,
      '#title' => t('Slider Type'),
      '#default_value' => isset($attrs['slide_anim']) ? $attrs['slide_anim'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $form['slide_autoplay'] = array(
      '#type' => 'textfield',
      '#title' => t('Autoplay (in milliseconds)'),
      '#default_value' => isset($attrs['slide_autoplay']) ? $attrs['slide_autoplay'] : '',
      '#description' => t('8000 is mean 8seconds'),
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );  
    return $form;
  }
}