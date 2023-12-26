<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "col",
 *   title = @Translation("Column"),
 *   description = @Translation("Bootstrap Column"),
 *   icon = "fa fa-columns"
 * )
 */
class ColShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {

    $attrs['class'] = isset($attrs['class']) ? $attrs['class'] : '';
    if(isset($attrs['phone'])) {
      $attrs['class'] .= ' col-xs-' . $attrs['phone'];
    }
    if(isset($attrs['tablet'])) {
      $attrs['class'] .= ' col-sm-' . $attrs['tablet'];
    }
    if(isset($attrs['desktop'])) {
      $attrs['class'] .= ' col-md-' . $attrs['desktop'];
    }
    if(isset($attrs['wide'])) {
      $attrs['class'] .= ' col-lg-' . $attrs['wide'];
    }
    $text = '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' . $text . '</div>';
    return $text;

  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['container'] = array(
      '#type' => 'container',
      '#prefix' => '<div class = "row col-settings device-icons-wrap">',
      '#suffix' => '</div>'
    );
    $options = array('' => t('Auto'), 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12);
    $form['container']['phone'] = array(
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => isset($attrs['phone']) ? $attrs['phone'] : 0,
      '#prefix' => '<div class = "col-xs-3 centered"><label class="sr-only" for="col-xs"><i class="fa fa-mobile fa-5x"></i></label>',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('form-control'))
    );
    $form['container']['tablet'] = array(
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => isset($attrs['tablet']) ? $attrs['tablet'] : 0,
      '#prefix' => '<div class = "col-xs-3 centered"><label class="sr-only" for="col-xs"><i class="fa fa-tablet fa-5x"></i></label>',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('form-control'))
    );
    $form['container']['desktop'] = array(
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => isset($attrs['desktop']) ? $attrs['desktop'] : 0,
      '#prefix' => '<div class = "col-xs-3 centered"><label class="sr-only" for="col-xs"><i class="fa fa-laptop fa-5x"></i></label>',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('form-control'))
    );
    $form['container']['wide'] = array(
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => isset($attrs['wide']) ? $attrs['wide'] : 0,
      '#prefix' => '<div class = "col-xs-3 centered"><label class="sr-only" for="col-xs"><i class="fa fa-desktop fa-5x"></i></label>',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('form-control'))
    );
    return $form;
  }
}