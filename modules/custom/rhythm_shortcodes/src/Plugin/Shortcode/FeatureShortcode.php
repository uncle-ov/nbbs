<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_feature",
 *   title = @Translation("Feature"),
 *   description = @Translation("Feature Box"),
 *   icon = "fa fa-gears",
 * )
 */
class FeatureShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    if (isset($attrs['type'])) {
      $type = $attrs['type'] == 'ci' ? 'contact' : $attrs['type'];
    }
    else{
      $type = 'alt-features';
    }
    $attrs['class'] = isset($attrs['class']) ? $attrs['class'] . ' ' . $type . '-item' : $type . '-item';
    $attrs['type'] = isset($attrs['type']) ? $attrs['type'] : '';
    switch ($attrs['type']) {
      case 'benefit':
        $icon = isset($attrs['icon']) ? '<div class="' . $attrs['type'] . '-icon"><i class="' . $attrs['icon'] . '"></i></div>' : '';
        $text = isset($text) && $text <> '' ? '<div class="' . $attrs['type'] . 's-descr">' . $text . '</div>' : '';
        $text = $icon . (isset($attrs['text']) ? '<h3 class="' . $attrs['type'] . '-title font-alt">' . $attrs['text'] . '</h3>' : '') . $text;
        break;
      case 'alt-service':
        $icon = isset($attrs['icon']) ? '<div class="' . $attrs['type'] . '-icon"><i class="' . $attrs['icon'] . '"></i></div>' : '';
        $text = isset($text) && $text <> '' ? $text : '';
        $text = $icon . (isset($attrs['text']) ? '<h3 class="' . $attrs['type'] . 's-title font-alt">' . $attrs['text'] . '</h3>' : '') . $text;  
        break;
      case 'ci':
        $icon = isset($attrs['icon']) ? '<div class="ci-icon"><i class="' . $attrs['icon'] . '"></i></div>' : '';
        $text = isset($text) && $text <> '' ? '<div class="ci-text">' . $text . '</div>' : '';
        $text = $icon . (isset($attrs['text']) ? '<div class="ci-title font-alt">' . $attrs['text'] . '</div>' : '') . $text;
        break;
      case 'medium':
        $icon = isset($attrs['icon']) ? '<div class="section-icon"><span class="' . $attrs['icon'] . '"></span></div>' : '';
        $text = $icon . (isset($attrs['text']) ? '<h3 class="small-title font-alt">' . $attrs['text'] . '</h3>' : '') . $text;
        break;
      default:
        $icon = isset($attrs['icon']) ? '<div class="' . $attrs['type'] . '-icon"><span class="' . $attrs['icon'] . '"></span></div>' : '';
        $align = $attrs['type'] == 'alt-features' ? 'align-left' : '';
        if ($icon != '') {
          $text = isset($text) && $text <> '' ? '<div class="' . $attrs['type'] . '-descr ' . $align . '">' . $text . '</div>' : '';
          $text = $icon . (isset($attrs['text']) ? '<h3 class="' . $attrs['type'] . '-title font-alt">' . $attrs['text'] . '</h3>' : '') . $text;
        }  
        else{
          $text = (isset($attrs['text']) ? '<h4 class="mt-0 font-alt">' . $attrs['text'] . '</h4>' : '') . $text;
          $text = '<div class="' . $attrs['type'] . '-descr ' . $align . '">' . $text . '</div>';
        }
        $attrs['class'] .= ' align-center';
    }
    $text = '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' . $text . '</div>';
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['text'] = array(
      '#title' => t('Text'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['text']) ? $attrs['text'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
    );
    $form['icon'] = array(
      '#title' => t('Icon'),
      '#type' => 'textfield',
      '#autocomplete_path' => 'admin/ajax/rhythm_shortcodes/icons_autocomplete',
      '#default_value' => isset($attrs['icon']) ? $attrs['icon'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-4">',
    );
    $type = array('alt-features' => t('Big Icon'), 'medium' => t('Medium Icon'), 'features' => t('Features'), 'benefit' => t('Benefit'), 'alt-service' => t('Left Small Icon'), 'ci' => t('Contact'));  
    $form['type'] = array(
      '#title' => t('Type'),
      '#type' => 'select',
      '#options' => $type,
      '#default_value' => isset($attrs['type']) ? $attrs['type'] : 'alt-features',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-4">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}