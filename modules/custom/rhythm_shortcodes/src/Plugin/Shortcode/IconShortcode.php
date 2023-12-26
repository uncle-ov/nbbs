<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_icon",
 *   title = @Translation("Icon"),
 *   description = @Translation("Icon with its name"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-image",
 * )
 */
class IconShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['class'] = (isset($attrs['class']) ? $attrs['class'] : '') . ' ' . (isset($attrs['icon']) ? $attrs['icon'] : '');
    $output = '<i ' . _rhythm_shortcodes_shortcode_attributes($attrs) . (isset($attrs['size']) ? ' style = "font-size:' . $attrs['size'] . 'px;"' : '') . '></i>';
    $attrs['link'] = isset($attrs['link']) && $attrs['link'] ? $attrs['link'] : '#';
    $class = isset($attrs['boxed']) && $attrs['boxed'] ? 'box1 ' : '';
    if(isset($attrs['type']) && $attrs['type'] == 'Big Icon') {
      $output = '<span class = "big-icon">' . $output . '</span>';
      $class .= ' big-icon-link';
    }
    if(strpos($attrs['link'], 'vimeo') !== FALSE || strpos($attrs['link'], 'youtu') !== FALSE) {
      $class = isset($attrs['no_lightbox']) && $attrs['no_lightbox'] ? '' : ' lightbox-gallery-1 mfp-iframe';
    }
    $target = isset($attrs['target']) && $attrs['target'] ? 'target = "_blank"' : '';
    $output = '<a href="' . $attrs['link'] . '" ' . $target . ' class = "' . $class . '">' . $output . '</a>';
    if(isset($attrs['type']) && $attrs['type'] == 'Bordered') {
      $output = '<span class = "footer-social-links">' . $output . '</span>';
    }

    return $output;
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
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $form['size'] = array(
      '#title' => t('Icon size (px)'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['size']) ? $attrs['size'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-2">',
    );
    $form['boxed'] = array(
      '#type' => 'checkbox',
      '#title' => t('Wrap in the Box'),
      '#default_value' => isset($attrs['boxed']) ? $attrs['boxed'] : FALSE,
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    $form['link'] = array(
      '#title' => t('Link'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['link']) ? $attrs['link'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $options = array('Default', 'Bordered', 'Big Icon');
    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Type'),
      '#options' => array_combine($options, $options),
      '#default_value' => isset($attrs['type']) ? $attrs['type'] : 'Default',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );
    $form['target'] = array(
      '#type' => 'checkbox',
      '#title' => t('Open in new window'),
      '#default_value' => isset($attrs['target']) ? $attrs['target'] : FALSE,
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
    );
    $form['no_lightbox'] = array(
      '#type' => 'checkbox',
      '#title' => t('Do not show video in lightbox'),
      '#default_value' => isset($attrs['no_lightbox']) ? $attrs['no_lightbox'] : FALSE,
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}