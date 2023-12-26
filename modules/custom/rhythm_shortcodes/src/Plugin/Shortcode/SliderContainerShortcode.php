<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;
use Drupal\image\Entity\ImageStyle;

/**
 * @Shortcode(
 *   id = "nd_slider",
 *   title = @Translation("Slider container"),
 *   description = @Translation("Slider container"),
 *   icon = "fa fa-arrows-h",
 *   child_shortcode = "nd_slider_item",
 *   description_field = "title"
 * )
 */
class SliderContainerShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['slide_anim'] = isset($attrs['slide_anim']) ? $attrs['slide_anim'] : '';
    $attrs['class'] = 'slider-wrapper';
    switch ($attrs['slide_anim']) {
      case 'wide' :
        $text = '<div class="item-carousel owl-carousel owl-theme" style="opacity: 1; display: block;">' . $text . '</div>';
        break; 
      case 'small_wide' :
        $text = '<div class="small-item-carousel black owl-carousel mb-0 animate-init" data-anim-type="fade-in-right-large" data-anim-delay="100">' . $text . '</div>';
        break; 
      case 'fullwidth' :
        $text = '<div class="fullwidth-slider owl-carousel bg-gray owl-theme" style="opacity: 1; display: block;">' . $text . '</div>';
        break;
      case 'fullwidth-zoom' :
        $text = '<div class="fullwidth-slider-fade owl-carousel bg-gray owl-theme" style="opacity: 1; display: block;">' . $text . '</div>';
        break;
      case 'fullwidth-bg' :
        $text = '<div class="fullwidth-gallery owl-carousel bg-gray owl-theme" style="opacity: 1; display: block;">' . $text . '</div>';
        break;
      case 'images_pager':
        $text = '<div class="relative"><div class="home-section fullwidth-slideshow black-arrows bg-dark">' . $text . '</div>';
        $pager = '<div class="fullwidth-slideshow-pager-wrap"><div class="container"><div class="row"><div class="col-md-8 col-md-offset-2"><div class="fullwidth-slideshow-pager">';
        preg_match_all("/uri = '([^']*)/i", $text, $matches);
        foreach ($matches[1] as $value) {
          $url = ImageStyle::load('thumbnail')->buildUrl($value);
          $rendered_image = '<img src="' . $url . '" />';
          $pager .= '<div class="fsp-item">' . $rendered_image . '</div>';
        }
        $pager .= '</div></div></div></div></div></div>';
        $text .= $pager;
        break;
      default:
        $text = '<div class="work-full-media mt-0 white-shadow">
          <div class="clearlist work-full-slider owl-carousel owl-theme" style="opacity: 1; display: block;">' . $text . '</div>
        </div>';
    }
    return '<div ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . ' data-autoplay = "' . (isset($attrs['slide_autoplay']) ? $attrs['slide_autoplay'] : '') . '">' . $text . '</div>';
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $types = array('' => t('Simple'), 'fullwidth' => t('Fullwidth'), 'fullwidth-zoom' => t('Fullwidth Zoom Effect'), 'fullwidth-bg' => t('Fullwidth Background'), 'wide' => t('Wide Autoscroll'), 'small_wide' => t('Wide Small Autoscroll'), 'images_pager' => t('Images Pager'));
    $form['slide_anim'] = array(
      '#type' => 'select',
      '#options' => $types,
      '#title' => t('Slider Type'),
      '#default_value' => isset($attrs['slide_anim']) ? $attrs['slide_anim'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $form['slide_autoplay'] = array(
      '#type' => 'textfield',
      '#title' => t('Autoplay (in milliseconds)'),
      '#default_value' => isset($attrs['slide_autoplay']) ? $attrs['slide_autoplay'] : '',
      '#description' => t('8000 is mean 8seconds'),
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}