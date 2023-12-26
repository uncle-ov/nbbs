<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_progress",
 *   title = @Translation("Progress Bar"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   description = @Translation("Progress Bar line"),
 *   icon = "fa fa-tasks",
 * )
 */
class ProgressShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['class'] = (isset($attrs['class']) ? $attrs['class'] : '') . ' progress-bar ';
    $percent = isset($attrs['percent']) && $attrs['percent'] ? $attrs['percent'] : 0;
    if(isset($attrs['type']) && $attrs['type'] == t('Thin Line')) { 
      $output = '<div class="progress tpl-progress">';
      $output .= '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) . ' aria-valuemax="100" aria-valuemin="0" aria-valuenow="' . $percent . '" role="progressbar" style="width: ' . $percent . '%;">';
      $output .= $attrs['title'] . ', %<span>' . $percent . '</span>
      </div>';
    }
    else{
      $output = '<div class="progress tpl-progress-alt">';
      $output .= '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) . ' aria-valuemax="100" aria-valuemin="0" aria-valuenow="' . $percent . '" role="progressbar" style="width: ' . $percent . '%;">';
      $output .= $attrs['title'] . ', <span>' . $percent . '%</span>
      </div>';
    }  
    $output .= '</div>';
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
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $form['percent'] = array(
      '#type' => 'textfield',
      '#title' => t('Percent'),
      '#default_value' => isset($attrs['percent']) ? $attrs['percent'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );
    $types = array(t('Wide Line'), t('Thin Line'));
    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Type'),
      '#options' => array_combine($types, $types),
      '#default_value' => isset($attrs['type']) ? $attrs['type'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}