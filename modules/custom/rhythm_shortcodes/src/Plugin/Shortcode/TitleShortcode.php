<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_title",
 *   title = @Translation("Title"),
 *   description = @Translation("Progress Bar line"),
 *   description_field = "title",
 *   icon = "fa fa-h-square",
 * )
 */
class TitleShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['class'] = isset($attrs['class']) ? $attrs['class'] : '';
    $attrs['class'] .= isset($attrs['alignment']) ? $attrs['alignment'] : '';
    $attrs['class'] .= isset($attrs['styled']) && $attrs['styled']? ' font-alt' : '';
    $attrs['class'] .= isset($attrs['uppercase']) && $attrs['uppercase'] ? ' uppercase' : '';
    $attrs['class'] .= isset($attrs['color']) ? ' ' . $attrs['color'] : '';
    $attrs['class'] .= isset($attrs['spacing']) ? ' ' . $attrs['spacing'] : '';
    $attrs['class'] .= isset($attrs['weight']) ? ' ' . $attrs['weight'] : '';
    $size = isset($attrs['size']) ? $attrs['size'] : 'h3';
    if($size == 'h1_big') {
      $attrs['class'] .= ' hs-line-1';
      $size = 'h1';
    }
    if($size == 'h1_80') {
      $attrs['class'] .= ' hs-line-12';
      $size = 'h1';
    }
    if($size == 'h2_styled') {
      $attrs['class'] .= ' hs-line-11';
      $size = 'h2';
    }
    if($size == 'h2_42') {
      $attrs['class'] .= ' hs-line-14';
      $size = 'h2';
    }
    if($size == 'h2_64') {
      $attrs['class'] .= ' hs-line-7';
      $size = 'h2';
    }
    if($size == 'h4_styled') {
      $attrs['class'] .= ' hs-line-8';
      $size = 'h4';
    }
    if($size == 'h6_big') {
      $attrs['class'] .= ' hs-line-6';
      $size = 'h6';
    }
    $text = '<'. $size . ' ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' . (isset($attrs['title']) ? $attrs['title']  : '' ). ($text ? $text : '') . '</' . $size . '>';
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
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $size = array('h1' => t('H1'), 'h1_big' => t('H1 Big letter spaces'), 'h1_80' => t('H1 80px'), 'h2' => t('H2'), 'h2_styled' => t('H2 Styled'), 'h2_64' => t('H2 64px'), 'h2_42' => t('H2 48px'), 'h3' => t('H3'), 'h4' => t('H4'), 'h4_styled' => t('H4 Small letter spaces'), 'h5' => t('H5'), 'h6' => t('H6'), 'h6_big' => t('H6 transparent'));
    $form['size'] = array(
      '#title' => t('Size'),
      '#type' => 'select',
      '#options' => $size,
      '#default_value' => isset($attrs['size']) ? $attrs['size'] : 'h4',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
    );
    $spacing = array('' => t('Default'), 'ls-01' => t('0.1em'), 'ls-02' => t('0.2em'), 'ls-03' => t('0.3em'), 'ls-04' => t('0.4em'), 'ls-05' => t('0.5em'), 'ls-06' => t('0.6em'), 'ls-07' => t('0.7em'), 'ls-08' => t('0.8em'), 'ls-09' => t('0.9em'), 'ls-10' => t('1.0em'));
    $form['spacing'] = array(
      '#title' => t('Letter Spacing'),
      '#type' => 'select',
      '#options' => $spacing,
      '#default_value' => isset($attrs['spacing']) ? $attrs['spacing'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    $alignment = array(' align-left' => t('Left'), ' align-center' => t('Center'), ' align-right' => t('Right'));
    $form['alignment'] = array(
      '#title' => t('Alignment'),
      '#type' => 'select',
      '#options' => $alignment,
      '#default_value' => isset($attrs['alignment']) ? $attrs['alignment'] : ' align-left',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
    );
    $form['styled'] = array(
      '#title' => t('Styled font'),
      '#type' => 'checkbox',
      '#default_value' => isset($attrs['styled']) ? $attrs['styled'] : TRUE,
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
    );
    $form['uppercase'] = array(
      '#title' => t('Uppercase'),
      '#type' => 'checkbox',
      '#default_value' => isset($attrs['uppercase']) ? $attrs['uppercase'] : TRUE,
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
    );
    $color = array('black' => t('Black'), 'white' => t('White'), 'gray' => t('Gray'));
    $form['color'] = array(
      '#title' => t('Color'),
      '#type' => 'select',
      '#options' => $color,
      '#default_value' => isset($attrs['color']) ? $attrs['color'] : 'black',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    $weight = array('' => t('Default'), 'fw-300' => t('300'), 'fw-400' => t('400'), 'fw-600' => t('600'));
    $form['weight'] = array(
      '#title' => t('Font weight'),
      '#type' => 'select',
      '#options' => $weight,
      '#default_value' => isset($attrs['weight']) ? $attrs['weight'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}