<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_tabs",
 *   title = @Translation("Tabs Container"),
 *   description = @Translation("Header"),
 *   icon = "fa fa-folder-open",
 *   child_shortcode = "nd_tab"
 * )
 */
class TabsContainerShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    global $tab_content;
    global $tabs_counter;
    $attrs['class'] = isset($attrs['class']) ? $attrs['class'] : '';
    // Tab Links
    $tabs_counter = !$tabs_counter ? 1 : $tabs_counter + 1;
    $font_class = isset($attrs['type']) && $attrs['type'] == 'alt' ? ' font-alt' : '';
    $class = ' nav nav-tabs tpl' . (isset($attrs['type']) ? '-' . $attrs['type'] : '') . '-tabs animate' . $font_class;
    $tabs = '<ul class="' . $class . '">' . $text . '</ul>';
    
    if(isset($attrs['centered']) && $attrs['centered']) {
      $tabs = '<div class="align-center mb-40 mb-xs-30">' . $tabs . '</div>';
    }
    // Tab  Content
    $tab_class = ' tab-content tpl' . (isset($attrs['type']) ? '-' . $attrs['type'] : '') . '-tabs-cont section-text' . 
      (isset($attrs['centered']) && $attrs['centered'] ? ' align-center' : '');
    $content = '<div class = "' . $tab_class. '">' . $tab_content . '</div>';
    // Create tabs 
    $text = $tabs . $content; 
    // Check if there is any attributes
    $attrs = _rhythm_shortcodes_shortcode_attributes($attrs);
    $text = $attrs ? '<div ' . $attrs  . '>' . $text . '</div>' : $text;
    // Clear the global variable for next possible tabs
    $tab_content = '';
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $types = array('' => t('Standard'), 'minimal' => t('Minimal'), 'alt' => t('Big Icons'));
    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Type'),
      '#options' => $types,
      '#default_value' => isset($attrs['type']) ? $attrs['type'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
    );
    $form['centered'] = array(
      '#title' => t('Centered'),
      '#type' => 'checkbox',
      '#default_value' => isset($attrs['centered']) ? $attrs['centered'] : FALSE,
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>'
    );
    return $form;
  }
}