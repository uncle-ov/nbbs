<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_video",
 *   title = @Translation("Video"),
 *   description = @Translation("Video"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-video-camera",
 * )
 */
class VideoShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    if (!isset($attrs['url'])) {
      return '';
    }
    $attrs['class'] = isset($attrs['class']) ? ' ' . $attrs['class'] : '';
    $iframe_attrs = (isset($attrs['width']) ? 'width="' . $attrs['width'] .'"' : '') . (isset($attrs['height']) ? ' height ="' . $attrs['height'] . '"' : '');
    $attrs['class'] .= ' video';
    $video_url = '';
    if(strpos($attrs['url'], 'vimeo') !== FALSE) {
       preg_match('|/(\d+)|', $attrs['url'], $matches);
       $video_url = '//player.vimeo.com/video/' . $matches[1] . '?title=0&amp;byline=0&amp;portrait=0&amp;color=FFFFFF';
    }
    else if(strpos($attrs['url'], 'youtube') !== FALSE) {
       if (strpos($attrs['url'], '?v=') !== false) {
          $id = substr($attrs['url'], strpos($attrs['url'], '?v=') + 3);
          $video_url = '//www.youtube.com/embed/' . $id .'?theme=dark&color=white';
       }
       else $video_url = $attrs['url'];
    }
    $text = '
    <div ' . _rhythm_shortcodes_shortcode_attributes($attrs)  . '>
      <iframe src="' . $video_url . '" ' . $iframe_attrs . ' allowfullscreen = "allowfullscreen"></iframe>
    </div>';
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['url'] = isset($attrs['url']) && $attrs['url'] ? $attrs['url'] : $text;
    $form['url'] = array(
      '#type' => 'textfield' ,
      '#title' => t('Video Url'),
      '#default_value' => isset($attrs['url']) ? $attrs['url'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#description' => t('Supports: YouTube and Vimeo')
    );
    $form['width'] = array(
      '#type' => 'textfield' ,
      '#title' => t('Width'),
      '#default_value' => isset($attrs['width']) ? $attrs['width'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $form['height'] = array(
      '#type' => 'textfield' ,
      '#title' => t('Height'),
      '#default_value' => isset($attrs['height']) ? $attrs['height'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}