<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_counter",
 *   title = @Translation("Counter"),
 *   description = @Translation("Counter"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-sort-numeric-asc"
 * )
 */
class CounterShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $output = '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . '>
      ' . (isset($attrs['number']) ? '<div class="count-number">' . $attrs['number'] . '</div>' : '') . '
      <div class="count-descr font-alt">
        ' . (isset($attrs['icon'])  && $attrs['icon']? '<i class="' . $attrs['icon'] . '"></i>' : '') . '
        ' . (isset($attrs['title'])  && $attrs['title']? '<span class="count-title">' . $attrs['title'] . '</span>' : '') . '
      </div>
    </div>';
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['title'] = array(
      '#type' => 'textfield' ,
      '#title' => t('Title'),
      '#default_value' => isset($attrs['title']) ? $attrs['title'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $form['icon'] = array(
      '#title' => t('Icon'),
      '#type' => 'textfield',
      '#autocomplete_path' => 'admin/ajax/rhythm_shortcodes/icons_autocomplete',
      '#default_value' => isset($attrs['icon']) ? $attrs['icon'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
    );
    $form['number'] = array(
      '#type' => 'textfield' ,
      '#title' => t('Number'),
      '#default_value' => isset($attrs['number']) ? $attrs['number'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}