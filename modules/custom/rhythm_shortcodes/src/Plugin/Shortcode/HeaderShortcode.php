<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Link;
use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;
use Drupal\file\Entity\File;
use Drupal\Core\Routing\RouteObjectInterface;

/**
 * @Shortcode(
 *   id = "nd_header",
 *   title = @Translation("Header"),
 *   description = @Translation("Header"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-header",
 *   description_field = "title"
 * )
 */
class HeaderShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attrs['class'] = isset($attrs['class']) ? $attrs['class'] : '';
    $attrs['class'] .= isset($attrs['size']) ? ' ' . $attrs['size'] : '';
    if (isset($attrs['size']) && $attrs['size'] != 'small-section pt-30 pb-30') {
      $description = isset($attrs['description']) ? $attrs['description'] : '';
      $light_bg = array('bg-gray-lighter', 'bg-gray', 'bg-light-alfa-30');
      $description = $description ? ('<div class="hs-line-4 font-alt' . (in_array($attrs['type'], $light_bg) ? ' black' : '') . '">' . $description . '</div>') : '';
    }
    $title = isset($attrs['title']) ? $attrs['title'] : '';
    if (!$title) {
      $request = \Drupal::request();
      if ($route = $request->attributes->get(RouteObjectInterface::ROUTE_OBJECT)) {
        $title = \Drupal::service('title_resolver')->getTitle($request, $route);
      }
    }
    $title = $title ? ($title) : t('Home Page');
    $file = isset($attrs['fid']) && !empty($attrs['fid']) ? File::load($attrs['fid']) : '';
    $filename = isset($file->uri) ? \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri()) : '';
    $attrs['class'] .= isset ($attrs['type']) ? ' ' . $attrs['type'] : '';
    $attrs['data-background'] = $filename ? $filename : '';
    $attrs['style'] = $filename ? 'background-image: url("' . $filename . '");' : '';
    $attrs['style'] .= isset($attrs['size']) && $attrs['size'] == 'home-section parallax-2 fixed-height-small' ? ' background-position: 50% 0px;' : '';
    if (isset($attrs['size'])  && $attrs['size'] == 'home-section parallax-2 fixed-height-small') {
      $container = '
        <div class="js-height-parent container" style="height: 600px;">
        <div class="home-content">
        <div class="home-text header-align-left">';
      $container_end ='</div></div>';
    }
    else{
      $container = '<div class="relative container text-align-left">';
      $container_end ='';
    }
    $header_type = isset($attrs['header_type']) ? $attrs['header_type'] : '';

//    $breadcrumbs = array('#theme' => 'breadcrumb');
    $breadcrumb_build = [
      Link::createFromRoute(t('Home'), '<front>'),
    ];
    $breadcrumb = [
      '#theme' => 'breadcrumb',
      '#links' => $breadcrumb_build,
    ];
    $breadcrumbs = \Drupal::service('renderer')->render($breadcrumb);
    $breadcrumbs = str_replace('<ol', '<ol class="c-page-breadcrumbs c-theme-nav c-pull-right c-fonts-regular"', $breadcrumbs);

    switch ($header_type) {
      case 'centered':
        $header = '
          <div class="relative container align-center">
            <nav class="mod-breadcrumbs font-alt align-center" role="navigation" aria-labelledby="system-breadcrumb">
              ' . (isset($attrs['breadcrumbs']) && $attrs['breadcrumbs'] ? $breadcrumbs : '') . '
            </nav>
            <h1 class="hs-line-11 font-alt mb-0">' . $title . '</h1>' .
            (isset($description) ? $description : '') . '
          </div>';
        break;
      default:
        $header =
        '<div class="row">
          <div class="col-md-8">
            <h1 class="hs-line-11 font-alt mb-20 mb-xs-0">' . $title . '</h1>' .
            (isset($description) ? $description : '') .
          '</div>
          ' . (isset($attrs['breadcrumbs']) && $attrs['breadcrumbs'] ? '
          <div class="col-md-4 mt-30">
            <nav class="mod-breadcrumbs font-alt align-right" role="navigation" aria-labelledby="system-breadcrumb">
              ' . (isset($attrs['breadcrumbs']) && $attrs['breadcrumbs'] ? $breadcrumbs : '') . '
            </nav>
          </div>
          ' : '') . '
        </div>';
        break;
    }
    $output = '<section ' . _rhythm_shortcodes_shortcode_attributes($attrs) . '>' .
        $container .
        $header .
      '</div>' .
      $container_end .
    '</section>';
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $size = array('' => t('Default'), 'centered' => t('Centered'));
    $form['header_type'] = array(
      '#type' => 'select',
      '#title' => t('Header Type'),
      '#options' => $size,
      '#default_value' => isset($attrs['header_type']) ? $attrs['header_type'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-6">',
      '#suffix' => '</div></div>'
    );

    $form['fid'] = array(
      '#type' => 'textfield',
      '#title' => t('Image'),
      '#default_value' => isset($attrs['fid']) ? $attrs['fid'] : '',
      '#prefix' => '<div class = "row"><div class = "col-sm-8"><div class="image-gallery-upload ">',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('image-gallery-upload hidden')),
      '#field_suffix' => '<div class = "preview-image"></div><a href = "#" class = "vc-gallery-images-select button">' . t('Upload Image') .'</a><a href = "#" class = "gallery-remove button">' . t('Remove Image') .'</a>'
    );

    if(isset($attrs['fid'])){
      $file = \Drupal\file\Entity\File::load($attrs['fid']);
      if($file){
        $filename = $file->getFileUri();
        $filename = \Drupal\image\Entity\ImageStyle::load('medium')->buildUrl($filename);
        $form['fid']['#prefix'] = '<div class = "row"><div class = "col-sm-6"><div class="image-gallery-upload has_image">';
        $form['fid']['#field_suffix']=  '<div class = "preview-image"><img src="'.$filename.'"></div><a href = "#" class = "vc-gallery-images-select button">' . t('Upload Image') .'</a><a href = "#" class = "gallery-remove button">' . t('Remove Image') .'</a>';
      }
    }

    $size = array ('small-section pt-30 pb-30' => t('Small'), 'small-section' => t('Normal'), 'page-section' => t('Large'), 'home-section parallax-2 fixed-height-small' => t('Extra Large'));
    $form['size'] = array(
      '#type' => 'select',
      '#title' => t('Size'),
      '#options' => $size,
      '#default_value' => isset($attrs['size']) ? $attrs['size'] : 'small-section',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>'
    );
    $types = array('bg-gray-lighter' => t('Gray lighter'), 'bg-gray'=>t('Gray'), 'bg-light-alfa-30' => t('Light A30'), 'bg-dark-lighter' => t('Dark lighter'), 'bg-dark' => t('Dark'), 'bg-dark-alfa-30' => t('Dark A30'), 'bg-dark-alfa-50' => t('Dark A50'), 'bg-dark-alfa-70' => t('Dark A70'));
    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Background Type'),
      '#options' => $types,
      '#default_value' => isset($attrs['type']) ? $attrs['type'] : 'bg-gray-lighter',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
    );
    $form['style_height'] = array(
      '#type' => 'textfield',
      '#title' => t('Height'),
      '#default_value' => isset($attrs['style_height']) ? $attrs['style_height'] : '',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '</div><div class = "col-sm-4">',
    );
    $form['breadcrumbs'] = array(
      '#type' => 'checkbox',
      '#title' => t('Breadcrumbs'),
      '#default_value' => isset($attrs['breadcrumbs']) ? $attrs['breadcrumbs'] : TRUE,
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>'
    );
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Override title'),
      '#default_value' => isset($attrs['title']) ? $attrs['title'] : '',
      '#attributes' => array('class' => array('form-control'))
    );
    $form['description'] = array(
      '#type' => 'textfield',
      '#title' => t('Description'),
      '#default_value' => isset($attrs['description']) ? $attrs['description'] : '',
      '#attributes' => array('class' => array('form-control'))
    );
    return $form;
  }
}
