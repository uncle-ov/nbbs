<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_quote",
 *   title = @Translation("Quote"),
 *   description = @Translation("Quote for text"),
 *   icon = "fa fa-quote-right",
 *   child_shortcode = "html"
 * )
 */
class QuoteShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['class'] = (isset($attrs['class']) ? $attrs['class'] : '') . (isset($attrs['type']) && $attrs['type'] == 'testimonial' ? ' testimonial' : '');
    $attrs['class'] .= isset($attrs['type']) && $attrs['type'] == 'blog-item-q' ? ' blog-item-q' : '';
    if (isset($attrs['type']) && $attrs['type'] != '' && $attrs['type'] != 'blog-item-q') {
      $text .= '<footer' . ($attrs['type'] == 'testimonial' ? ' class="testimonial-author"' : '') . '>' . 
        (isset($attrs['footer']) ? $attrs['footer'] : '') . 
        (isset($attrs['cite']) ? '<cite title="' . $attrs['cite'] . '">' . $attrs['cite'] . '</cite>' : '') . 
      '</footer>';
    }
    $output = '<blockquote ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . '>' . $text . '</blockquote>';
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
     $type = array('' => t('Only Text'), 'blog-item-q' => t('Only Text with Background'), 'footer' => t('Default Quote'), 'testimonial' => t('Testimonial'));
    $states =  array(
      'visible' => array(
        '.quote-type-select' => array('!value' => ''),
        '.quote-type-select, a' => array('!value' => 'blog-item-q'),
      ),
    );
    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Type'),
      '#options' => $type,
      '#default_value' => isset($attrs['type']) ? $attrs['type'] : '',
      '#attributes' => array('class' => array('form-control', 'quote-type-select')),
    );
    $form['footer'] = array(
      '#type' => 'textfield',
      '#title' => t('Author'),
      '#default_value' => isset($attrs['footer']) ? $attrs['footer'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
      '#states' => $states
    );
    $form['cite'] = array(
      '#type' => 'textfield',
      '#title' => t('Description'),
      '#default_value' => isset($attrs['cite']) ? $attrs['cite'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
      '#states' => $states
    );
    return $form;
  }
}