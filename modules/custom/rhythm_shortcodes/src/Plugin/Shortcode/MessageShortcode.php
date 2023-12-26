<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_message",
 *   title = @Translation("Message"),
 *   description = @Translation("Message text"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-exclamation",
 * )
 */
class MessageShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['class'] = (isset($attrs['class']) ? $attrs['class'] . ' ' : '') . $attrs['message_type']; 
    switch ($attrs['message_type']) {
      case 'alert info':
          $text = '<i class="fa fa-lg fa-comments-o"></i> ';
          break;
      case 'alert success':
          $text = '<i class="fa fa-lg fa-check-circle-o"></i> ';
          break;
      case 'alert notice':
          $text = '<i class="fa fa-lg fa-exclamation-triangle"></i> ';
          break;
      case 'alert error':
          $text = '<i class="fa fa-lg fa-times-circle"></i> ';
          break;
    }
    $output = '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' . $text . $attrs['text'] . '</div>';
    return $output;
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
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $message_type = array('alert info' => t('Info'), 'alert success' => t('Success'), 'alert notice' => t('Notice'), 'alert error' => t('Error'));
    $form['message_type'] = array(
      '#type' => 'select',
      '#title' => t('Message type'),
      '#options' => $message_type,
      '#default_value' => isset($attrs['message_type']) ? $attrs['message_type'] : FALSE,
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    return $form;  
  }
}