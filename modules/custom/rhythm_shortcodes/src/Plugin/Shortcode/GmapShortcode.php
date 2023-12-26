<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_gmap",
 *   title = @Translation("Google Map"),
 *   description = @Translation("Google Map"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-map-marker",
 * )
 */
class GmapShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    if (!isset( $attrs['type'])) return '';
    $attrs['class'] = 'google-map' . (isset($attrs['class']) ? ' ' . $attrs['class'] : '');
    $text = '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>
      <div id="map-canvas" style="height: ' . (isset($attrs['height']) ? $attrs['height'] : 410) . 'px;"
        data-zoom="' . (isset($attrs['zoom']) ? $attrs['zoom'] : 14) . '"
        data-address="' . (isset($attrs['address']) ? $attrs['address'] : '') . '"
        data-type="' . $attrs['type'] . '">
      </div>
      ' . (isset($attrs['overlay']) && $attrs['overlay'] ?
        '<div class="map-section">
          <div class="map-toggle">
            <div class="mt-icon">
              <i class="fa fa-map-marker"></i>
            </div>
            <div class="mt-text font-alt">
              <div class="mt-open">' . t('Open the map') . ' <i class="fa fa-angle-down"></i></div>
              <div class="mt-close">' . t('Close the map') . ' <i class="fa fa-angle-up"></i></div>
            </div>
          </div>          
        </div>' : '' ) . '
    </div>';
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $types = array('ROADMAP' => t('Roadmap'), 'HYBRID' => t('Hybrid'), 'SATELLITE' => t('Satellite'));
    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Type'),
      '#options' => $types,
      '#default_value' => isset($attrs['type']) ? $attrs['type'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $form['overlay'] = array(
      '#type' => 'checkbox',
      '#title' => t('Overlay Open/Close'),
      '#default_value' => isset($attrs['overlay']) ? $attrs['overlay'] : TRUE,
      '#prefix' => '</div><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );
    $form['address'] = array(
      '#type' => 'textfield',
      '#title' => t('Address'),
      '#default_value' => isset($attrs['address']) ? $attrs['address'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $form['zoom'] = array(
      '#type' => 'textfield',
      '#title' => t('Zoom'),
      '#default_value' => isset($attrs['zoom']) ? $attrs['zoom'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
    );
    $form['height'] = array(
      '#type' => 'textfield',
      '#title' => t('Height'),
      '#default_value' => isset($attrs['height']) ? $attrs['height'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }

}
