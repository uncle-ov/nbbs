<?php

/**
 * @file
 * Contains \Drupal\rhythm_cms\Plugin\Shortcode\ButtonShortcode.
 */

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * The image shortcode.
 *
 * @Shortcode(
 *   id = "nd_accordion",
 *   title = @Translation("Accordion Item"),
 *   description = @Translation("Accordion Item"),
 *   child_shortcode = "nd_accordion",
 *   icon = "fa fa-minus",
 *   description_field = "title"
 * )
 */
class AccordionShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {

    $icon = isset($attrs['icon']) && $attrs['icon']  ? '<i class="' . $attrs['icon'] . '"></i> ' : '';
    $text = '<dt ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . '>
      <a href="#">
        ' . $icon . $attrs['title'] . '
      </a>
    </dt>
    <dd>
        ' . $text . '
    </dd>';
    return $text;

  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['title'] = array(
      '#type' => 'textfield' ,
      '#title' => t('Title'),
      '#default_value' => isset($attrs['title']) ? $attrs['title'] : '',
      '#attributes' => array('class' => array('form-control'))
    );
    $form['icon'] = array(
      '#title' => t('Icon'),
      '#type' => 'textfield',
      '#autocomplete_path' => 'admin/ajax/rhythm_shortcodes/icons_autocomplete',
      '#default_value' => isset($attrs['icon']) ? $attrs['icon'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}