<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_text_n_tooltip",
 *   title = @Translation("Text & Tooltip"),
 *   description = @Translation("Some text with tooltip"),
 *   icon = "fa fa-file-text-o",
 * )
 */
class TextTooltipShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['class'] = (isset($attrs['class']) ? $attrs['class'] : '') . ' text'; 
    $text = isset($attrs['title']) ? '<' . $attrs['title_size'] . ' class="uppercase">' . $attrs['title'] . '</' . $attrs['title_size'] .'>' : '';
    $text .= isset($attrs['first_text']) ? $attrs['first_text'] : '';
    $text .= isset($attrs['tooltip']) ? '<a class="tooltip-top" title="" href="#" data-original-title="' . $attrs['tooltip_popup'] . '">' . $attrs['tooltip'] . '</a>' : '';
    $text .= isset($attrs['second_text']) ? $attrs['second_text'] : '';
    $text = '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' . $text . '</div>';
    return $text; 
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['title'] = array(
      '#title' => t('Title'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['title']) ? $attrs['title'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
    );
    $title_size = array('H1' => t('H1'), 'H2' => t('H2'), 'H3' => t('H3'), 'H4' => t('H4'), 'H5' => t('H5'));
    $form['title_size'] = array(
      '#title' => t('Title size'),
      '#type' => 'select',
      '#options' => $title_size,
      '#default_value' => isset($attrs['title_size']) ? $attrs['title_size'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-4">',
      '#suffix' => '</div></div>',
    );  
    $form['first_text'] = array(
      '#title' => t('First text'),
      '#type' => 'textarea',
      '#default_value' => isset($attrs['first_text']) ? $attrs['first_text'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );
    $form['tooltip'] = array(
      '#title' => t('Tooltip'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['tooltip']) ? $attrs['tooltip'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
    );
    $form['tooltip_popup'] = array(
      '#title' => t('Tooltip Popup'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['tooltip_popup']) ? $attrs['tooltip_popup'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-4">',
      '#suffix' => '</div></div>',
    );
    $form['second_text'] = array(
      '#title' => t('Second text'),
      '#type' => 'textarea',
      '#default_value' => isset($attrs['second_text']) ? $attrs['second_text'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}