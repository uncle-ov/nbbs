<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_tab",
 *   title = @Translation("Tab"),
 *   description = @Translation("Tab"),
 *   icon = "fa fa-folder",
 *   description_field = "title"
 * )
 */
class TabShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    global $tab_counter;
    global $tab_content;
    $tab_counter = !$tab_counter ? rand(0, 999999) : ++$tab_counter;
    // Tab Link
    $icon = isset($attrs['icon']) ? (isset($attrs['icon_type']) && $attrs['icon_type'] == 'big' ? '<div class="alt-tabs-icon"><span class="' . $attrs['icon'] .'"></span></div>' : '<i class = "' . $attrs['icon'] .'"></i>') : '';

    $class = isset($attrs['active']) && $attrs['active'] ? 'class = "active"' : '';
    $output = '<li ' . $class  . '><a data-toggle="tab" href = "#tab-' . $tab_counter . '" aria-expanded="' . (isset($attrs['active']) && $attrs['active'] ? 'true' : 'false') . '">' . $icon . (isset($attrs['title']) ? $attrs['title'] : '') . '</a></li>';
    // Tab Content
    $attrs['class'] = (isset($attrs['class']) ? $attrs['class'] : '') . ' tab-pane fade';
    $attrs['class'] .= isset($attrs['active']) && $attrs['active'] ? ' active in' : '';
    $attrs['id'] = 'tab-' . $tab_counter;
    $tab_content .= '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . '>' . $text . '</div>';

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => isset($attrs['title']) ? $attrs['title'] : '',
      '#attributes' => array('class' => array('form-control')),
    );
    $form['icon'] = array(
      '#title' => t('Icon'),
      '#type' => 'textfield',
      '#autocomplete_path' => 'admin/ajax/rhythm_shortcodes/icons_autocomplete',
      '#default_value' => isset($attrs['icon']) ? $attrs['icon'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $types = array('' => t('Default'), 'big' => t('Big'));
    $form['icon_type'] = array(
      '#type' => 'select',
      '#title' => t('Icon Type'),
      '#options' => $types,
      '#default_value' => isset($attrs['icon_type']) ? $attrs['icon_type'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
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