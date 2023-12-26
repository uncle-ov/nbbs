<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

/**
 * @Shortcode(
 *   id = "nd_image",
 *   title = @Translation("Image"),
 *   description = @Translation("Image"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-file-image-o"
 * )
 */
class ImageShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {

    $file = isset($attrs['fid']) && !empty($attrs['fid']) ? File::load($attrs['fid']) : '';
    $uri = $file != '' ? $file->getFileUri() : '';
    if(!$file  || !$uri) {
      return '';
    }
//    return $uri;

    $image = array(
      '#theme' => 'image',
      '#uri' => $uri
    );
    $attrs['class'] = isset($attrs['align']) && $attrs['align'] ? 'image-align text-align-' . $attrs['align'] : '';

    $image['#title'] = isset($attrs['title']) ? $attrs['title'] : '';
    $image['#alt'] = isset($attrs['alt']) ? $attrs['alt'] : '';

    $image['#attributes']['style'] = '';
    if(isset($attrs['width']) && $attrs['width']) {
      $attrs['width'] .= strpos($attrs['width'], '%') === FALSE ? 'px' : '';
      $image['#attributes']['style'] .= 'width:' . $attrs['width'] . ';';
    }
    if(isset($attrs['height']) && $attrs['height']) {
      $attrs['height'] .= strpos($attrs['height'], '%') === FALSE ? 'px' : '';
      $image['#attributes']['style'] .= 'height:' . $attrs['height'] . ';';
    }
    $styles = image_style_options();
    if(isset($attrs['image_style']) && $attrs['image_style'] && isset($styles[$attrs['image_style']])) {
      $image['#theme'] = 'image_style';
      $image['#style_name'] = $attrs['image_style'];
    }
    $img = \Drupal::service('renderer')->render($image);
    $attrs['href'] = isset($attrs['link']) && $attrs['link'] ? $attrs['link'] : '';
    $text = $attrs['href'] ? '<a ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' . $img . '</a>' : '<span ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' . $img . '</span>';
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $file = isset($attrs['fid']) && !empty($attrs['fid']) ? File::load($attrs['fid']) : '';
    $image = '';
    if($file != '' && $uri = $file->getFileUri()) {
      $image = array(
        '#theme' => 'image_style',
        '#uri' => $uri,
        '#style_name' => 'medium'
      );
    }

    $form['fid'] = array(
      '#type' => 'textfield',
      '#title' => t('Image'),
      '#default_value' => isset($attrs['fid']) ? $attrs['fid'] : '',
      '#prefix' => '<div class = "row"><div class = "col-sm-6"><div class="image-gallery-upload ">',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('image-gallery-upload hidden')),
      '#field_suffix' => '<div class = "preview-image"></div><a href = "#" class = "vc-gallery-images-select button">' . t('Upload Image') .'</a><a href = "#" class = "gallery-remove button">' . t('Remove Image') .'</a>'
    );

    if(isset($attrs['fid'])){
      $file = File::load($attrs['fid']);
      if($file){
        $filename = $file->getFileUri();
        $filename = \Drupal\image\Entity\ImageStyle::load('medium')->buildUrl($filename);
        $form['fid']['#prefix'] = '<div class = "row"><div class = "col-sm-6"><div class="image-gallery-upload has_image">';
        $form['fid']['#field_suffix']=  '<div class = "preview-image"><img src="'.$filename.'"></div><a href = "#" class = "vc-gallery-images-select button">' . t('Upload Image') .'</a><a href = "#" class = "gallery-remove button">' . t('Remove Image') .'</a>';
      }

    }
    $styles = image_style_options();
    $form['image_style'] = array(
      '#type' => 'select',
      '#title' => t('Image Style'),
      '#options' => $styles,
      '#default_value' => isset($attrs['image_style']) ? $attrs['image_style'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
    );
    $aligns = array('' => t(' - None - '), 'center' => t('Center'), 'left' => t('Left'), 'right' => t('Right'));
    $form['align'] = array(
      '#type' => 'select',
      '#title' => t('Align'),
      '#options' => $aligns,
      '#default_value' => isset($attrs['align']) ? $attrs['align'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
    );
    $form['link'] = array(
      '#type' => 'textfield',
      '#title' => t('Link'),
      '#default_value' => isset($attrs['link']) ? $attrs['link'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $form['width'] = array(
      '#type' => 'textfield',
      '#title' => t('Width'),
      '#default_value' => isset($attrs['width']) ? $attrs['width'] : '',
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
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => isset($attrs['title']) ? $attrs['title'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
    );
    $form['alt'] = array(
      '#type' => 'textfield',
      '#title' => t('Alt'),
      '#default_value' => isset($attrs['alt']) ? $attrs['alt'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-6">',
      '#suffix' => '</div></div>',
    );
    return $form;
  }
}
