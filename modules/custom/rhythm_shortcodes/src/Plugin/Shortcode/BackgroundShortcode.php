<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\file\Entity\File;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_bg",
 *   title = @Translation("Background"),
 *   description = @Translation("Background for your content"),
 *   icon = "fa fa-file-image-o",
 * )
 */
class BackgroundShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    global $parallax_id;
    $parallax_id = $parallax_id ? $parallax_id + 1: 0;
    $attrs['class'] = (isset($attrs['class']) ? $attrs['class'] : '') . (isset($attrs['color']) ? ' ' . $attrs['color'] : '') . ' page-section';
    $attrs['class'] .= (isset($attrs['banner']) && $attrs['banner']) ? ' parallax-' . $parallax_id : ' bg-scroll';
    $attrs['class'] .= (isset($attrs['slider']) && $attrs['slider']) ? ' fullwidth-slider bg-scroll owl-carousel owl-theme': '';

    $file = isset($attrs['fid']) && !empty($attrs['fid']) ? File::load($attrs['fid']) : '';
    if (isset($file->uri)) {
      _rhythm_shortcodes_shortcode_slider_pager_image($file->getFileUri());
      $attrs['data-background'] = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
      $attrs['data-uri'] = $file->getFileUri();
      $attrs['style'] = (isset($attrs['style']) ? $attrs['style'] : '') . 'background-image: url(' . \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri()) . ');';
      $attrs['class'] .= ' banner-section';
    }
    if (isset($attrs['video']) && $attrs['video']) {
      global $id;
      $id = !$id ? 1 : ++$id;
      $attrs['id'] = 'video-bg-' . $id;
      $mute = isset($attrs['video_sound']) && $attrs['video_sound'] ? 'false' : 'true';
      $text = '<div class="player" data-property="{videoURL:\'' . $attrs['video'] . '\',containment:\'#video-bg-' . $id . '\',autoPlay:true, showControls:true, showYTLogo: false, mute:' . $mute . ', startAt:0, opacity:1}"></div>' . $text;
    }
    if (isset($attrs['full_height']) && $attrs['full_height']) {
      $text = '<div class = "js-height-full">' . $text . '</div>';
      $attrs['class'] .= ' pt-0 pb-0';
    }
    $rand = rand(0, 99999);
    if (isset($attrs['scroll_icon']) && $attrs['scroll_icon']) {
      $text .= '<div class="local-scroll"><a href="#scroll' . $parallax_id . $rand . '" class="scroll-down"><i class="fa fa-angle-down scroll-down-icon"></i></a></div>';
    }
    $output = '<section ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' . $text . '</section>';
    if (isset($attrs['scroll_icon']) && $attrs['scroll_icon']) {
      $output .= '<span id = "scroll' . $parallax_id . $rand . '"></span>';
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $colors = array('bg-dark' => t('Dark'), 'bg-dark-alfa-30' => t('Dark A30'), 'bg-dark-alfa-50' => t('Dark A50'), 'bg-dark-alfa-70' => t('Dark A70'), 'bg-dark-alfa-90' => t('Dark A90'), 'bg-dark-lighter' => t('Dark Lighter'), 'bg-gray' => t('Gray'), 'bg-gray-lighter' => t('Gray Lighter'), '' => t('White'), 'bg-pattern-over' => t('Dotted Overlay'));
    $form['color'] = array(
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $colors,
      '#default_value' => isset($attrs['color']) ? $attrs['color'] : '',
      '#attributes' => array('class' => array('color-radios', 'form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
      '#suffix' => '</div>',
    );

    $form['fid'] = array(
      '#type' => 'textfield',
      '#title' => t('Image'),
      '#default_value' => isset($attrs['fid']) ? $attrs['fid'] : '',
      '#prefix' => '<div class = "col-sm-6"><div class="image-gallery-upload ">',
      '#suffix' => '</div></div></div>',
      '#attributes' => array('class' => array('image-gallery-upload hidden')),
      '#field_suffix' => '<div class = "preview-image"></div><a href = "#" class = "vc-gallery-images-select button">' . t('Upload Image') .'</a><a href = "#" class = "gallery-remove button">' . t('Remove Image') .'</a>'
    );

    if(isset($attrs['fid']) && !empty($attrs['fid'])) {
      $file = isset($attrs['fid']) && !empty($attrs['fid']) ? File::load($attrs['fid']) : '';
      if($file) {
        $filename = $file->getFileUri();
        $filename=\Drupal\image\Entity\ImageStyle::load('medium')->buildUrl($filename);
        $form['fid']['#prefix'] = '<div class = "row"><div class = "col-sm-6"><div class="image-gallery-upload has_image">';
        $form['fid']['#field_suffix']=  '<div class = "preview-image"><img src="'.$filename.'"></div><a href = "#" class = "vc-gallery-images-select button">' . t('Upload Image') .'</a><a href = "#" class = "gallery-remove button">' . t('Remove Image') .'</a>';
      }
    }

    $form['slider'] = array(
      '#type' => 'checkbox',
      '#title' => t('Background Slider'),
      '#default_value' => isset($attrs['slider']) ? $attrs['slider'] : FALSE,
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
    );
    $form['banner'] = array(
      '#type' => 'checkbox',
      '#title' => t('Parallax'),
      '#default_value' => isset($attrs['banner']) ? $attrs['banner'] : FALSE,
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
    );
    $form['full_height'] = array(
      '#type' => 'checkbox',
      '#title' => t('Full Height'),
      '#default_value' => isset($attrs['full_height']) ? $attrs['full_height'] : FALSE,
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
    );
    $form['scroll_icon'] = array(
      '#type' => 'checkbox',
      '#title' => t('Scroll Icon'),
      '#default_value' => isset($attrs['scroll_icon']) ? $attrs['scroll_icon'] : FALSE,
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    $form['video'] = array(
      '#type' => 'textfield',
      '#title' => t('Video Background'),
      '#default_value' => isset($attrs['video']) ? $attrs['video'] : '',
      '#attributes' => array('class' => array('form-control')),
    );
    $form['video_sound'] = array(
      '#type' => 'checkbox',
      '#title' => t('Video Sound'),
      '#default_value' => isset($attrs['video_sound']) ? $attrs['video_sound'] : FALSE,
    );
    return $form;
  }
}
