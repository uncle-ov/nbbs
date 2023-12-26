<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_pricing_table",
 *   title = @Translation("Pricing table"),
 *   description = @Translation("Pricing table"),
 *   icon = "fa fa-dollar",
 *   child_shortcode = "pricing_item"
 * )
 */
class PricingTableShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['class'] = isset($attrs['class']) ? $attrs['class'] : '';
    $icon = isset($attrs['icon']) ? '<div class="pricing-icon"><i class="' . $attrs['icon'] . '"></i></div>' : '';
    $link = isset($attrs['link']) ? $attrs['link'] : '#';

    $text = '<div class="pricing-item' . (isset($attrs['active']) && $attrs['active'] ? ' main' : '') . '">
      <div class="pricing-item-inner">
        <div class="pricing-wrap">' . $icon .
          (isset($attrs['title']) ? '<div class="pricing-title">' . $attrs['title'] . '</div>' : '') .
          (isset($text) ? '<div class="pricing-features font-alt"><ul class="sf-list pr-list">' . $text . '</ul></div>' : '') .
          (isset($attrs['price']) ? '<div class="pricing-num"><sup>' . (isset($attrs['sign']) ? $attrs['sign'] : '$') . 
          '</sup>' . (isset($attrs['price']) ? $attrs['price'] : '') . '</div>' : '') .
          (isset($attrs['period']) ? '<div class="pr-per"> ' . $attrs['period'] . ' </div>' : '') .
          (isset($attrs['button']) ? '<div class="pr-button"><a class="btn btn-mod" href="' . $link . '">' . $attrs['button'] . '</a></div>' : '') .
        '</div>
      </div>
    </div>'; 
    $text = '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' . $text . '</div>';
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['icon'] = array(
      '#title' => t('Icon'),
      '#type' => 'textfield',
      '#autocomplete_path' => 'admin/ajax/rhythm_shortcodes/icons_autocomplete',
      '#default_value' => isset($attrs['icon']) ? $attrs['icon'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
    );
    $form['title'] = array(
      '#title' => t('Title'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['title']) ? $attrs['title'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-4">',
      '#suffix' => '</div></div>',
    );
    $form['price'] = array(
      '#title' => t('Price'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['price']) ? $attrs['price'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
    );
    $form['sign'] = array(
      '#title' => t('Sign'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['sign']) ? $attrs['sign'] : '$',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-2">',
    );
    $form['period'] = array(
      '#title' => t('Period'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['period']) ? $attrs['period'] : 'per month',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    $form['button'] = array(
      '#title' => t('Button'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['button']) ? $attrs['button'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
    );
    $form['link'] = array(
      '#title' => t('Link'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['link']) ? $attrs['link'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-6">',
    );
    $form['active'] = array(
      '#title' => t('Active'),
      '#type' => 'checkbox',
      '#default_value' => isset($attrs['active']) ? $attrs['active'] : FALSE,
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>'
    );
    return $form;
  }
}