<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;
use Drupal\block\Entity\Block;

/**
 * @Shortcode(
 *   id = "nd_block",
 *   title = @Translation("Block"),
 *   description = @Translation("Render drupal block"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-file"
 * )
 */
class BlockShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $output = '';
    if (!empty($attrs['admin_url']) && strpos($attrs['admin_url'], 'admin/structure/block') !== FALSE) {
      $block_name = substr($attrs['admin_url'], strpos($attrs['admin_url'], '/manage/') + 8);
      $block = Block::load($block_name);
      if (!empty($block)) {
        $view_block = \Drupal::entityTypeManager()
          ->getViewBuilder('block')
          ->view($block);
        $output = \Drupal::service('renderer')->render($view_block);
      }
    }
    $attrs_output = _rhythm_shortcodes_shortcode_attributes($attrs);
    return $attrs_output ? '<div ' . $attrs_output  . '>' . $output . '</div>' : $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $current_theme = \Drupal::config('system.theme')->get('default');

    $blocks = \Drupal::entityQuery('block')
    ->condition('theme', $current_theme)
    ->execute();

    $options = array();
    foreach ($blocks as $id) {
      $block = Block::load($id);
      $options['admin/structure/block/manage/' . $id] = $block->label();
    }
    asort($options);
    $form['admin_url'] = array(
      '#title' => t('Block'),
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => isset($attrs['admin_url']) ? $attrs['admin_url'] : '',
      '#attributes' => array('class' => array('form-control'))
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function description($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    return '';
    if (strpos($attrs['admin_url'], 'admin/structure/block') !== FALSE) {
      $form = rhythm_shortcodes_shortcode_block_settings($attrs, $text);
      $value = l($form['admin_url']['#options'][$attrs['admin_url']], $attrs['admin_url'], array('attributes' => array('target' => '_blank')));
      return $value;
    }
  }
}
