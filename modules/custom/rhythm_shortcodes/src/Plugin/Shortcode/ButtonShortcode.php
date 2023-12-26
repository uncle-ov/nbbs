<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_button",
 *   title = @Translation("Button"),
 *   description = @Translation("Button Link"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-bold",
 * )
 */
class ButtonShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
   if (isset($attrs['invert']) && $attrs['invert']) {
      $attrs['class'] = (isset($attrs['class']) ? $attrs['class'] : '') . (isset($attrs['button_type']) ? 'btn btn-mod btn-' . $attrs['button_type'] . '-w' : 'btn btn-mod btn-w');
    }
    else{
      $attrs['class'] = (isset($attrs['class']) ? $attrs['class'] : '') . (isset($attrs['button_type']) ? 'btn btn-mod btn-' . $attrs['button_type'] : 'btn btn-mod ');
    }
    $attrs['class'] .= isset($attrs['size']) ? ' btn-' . $attrs['size'] : '';
    $attrs['class'] .= ' btn-' . $attrs['display'];
    $attrs['link'] = isset($attrs['link']) ? $attrs['link'] : '#';
    $attrs['href'] = strpos($attrs['link'], '#') === FALSE && strpos($attrs['link'], 'http') === FALSE ? base_path() . $attrs['link'] : $attrs['link'];
    $attrs['target'] = isset($attrs['target']) && $attrs['target'] ? '_blank' : '';
    $text = isset($attrs['text']) ? $attrs['text']: '';
    if(isset($attrs['icon']) && $attrs['icon']) {
      if (isset($attrs['icon_position']) && $attrs['icon_position']) {
          $text = '<i class="' . $attrs['icon'] . '"></i> ' . $text;
      }
      else{
          $attrs['class'] .= ' btn-icon';
          $text = '<span><i class="' . $attrs['icon'] . '"></i></span>' . $text;
      }
    }
    $text = '<a ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . '>' . $text . '</a>';
    $text = (isset($attrs['block']) && $attrs['block'] ? '<div class="mb-10">' . $text . '</div>' : $text);
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $button_type = array('' => t('Dark'), 'glass' => t('Glass'), 'gray' => t('Light'), 'border' => t('Bordered'));
    $form['button_type'] = array(
      '#type' => 'select',
      '#title' => t('Button type'),
      '#options' => $button_type,
      '#default_value' => isset($attrs['button_type']) ? $attrs['button_type'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-2">',
    );
    $form['block'] = array(
      '#type' => 'checkbox',
      '#title' => t('Display as block'),
      '#default_value' => isset($attrs['block']) ? $attrs['block'] : FALSE,
      '#prefix' => '</div><div class = "col-sm-3">',
    );
    $displays = array('round' => t('Round'), 'circle' => t('Circle'));
    $form['display'] = array(
      '#type' => 'select',
      '#title' => t('Display'),
      '#options' => $displays,
      '#default_value' => isset($attrs['display']) ? $attrs['display'] : t('round'),
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
    );
    $sizes = array('' => t('Small'),  'small' => t('Medium'), 'medium' => t('Big'), 'large' => t('Large'));
    $form['size'] = array(
      '#type' => 'select',
      '#title' => t('Size'),
      '#options' => $sizes,
      '#default_value' => isset($attrs['size']) ? $attrs['size'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-2">',
      '#suffix' => '</div></div>',
    );  
    $form['text'] = array(
      '#title' => t('Text'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['text']) ? $attrs['text'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
    );
    $form['link'] = array(
      '#type' => 'textfield',
      '#title' => t('Link'),
      '#default_value' => isset($attrs['link']) ? $attrs['link'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-4">',
    );
    $form['invert'] = array(
      '#type' => 'checkbox',
      '#title' => t('White'),
      '#default_value' => isset($attrs['invert']) ? $attrs['invert'] : FALSE,
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    $form['icon'] = array(
      '#title' => t('FontAwesome Icon'),
      '#type' => 'textfield',
      '#autocomplete_path' => 'admin/ajax/nd_visualshortcodes/icons_autocomplete/font_awesome',
      '#default_value' => isset($attrs['icon']) ? $attrs['icon'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
    );
    $icon_position = array('' => t('Backward'), 'beside' => t('Beside'));
    $form['icon_position'] = array(
      '#type' => 'select',
      '#title' => t('Icon position'),
      '#options' => $icon_position,
      '#default_value' => isset($attrs['icon_position']) ? $attrs['icon_position'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-4">',
    );
    $form['target'] = array(
      '#type' => 'checkbox',
      '#title' => t('Open in new window'),
      '#default_value' => isset($attrs['target']) ? $attrs['target'] : FALSE,
      '#prefix' => '</div><div class = "col-sm-4">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}