<?php

namespace Drupal\nd_visualshortcodes\Render\Element;

use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Provides PHP code text format. Use with care.
 *
 * @Format(
 *   id = "textformat",
 *   module = "nd_visualshortcodes",
 *   title = @Translation("Text format"),
 *   description = @Translation("Replace view in visual editor"),
 * )
 */


class TextFormat implements TrustedCallbackInterface {

  /**
   * @inheritDoc
   */
  public static function trustedCallbacks() {
      return ['preRender'];
  }

 /**
   * Pre-render callback to replace the default menu with a role specific menu.
   *
   * @param array $element
   *   The administration toolbar.
   *
   * @return array
   *   The altered element.
   */
  public static function preRender(array $element) {
    $user = \Drupal::currentUser();
    if (!$user->hasPermission('use nd nd_visualshortcodes')) {
      return $element;
    }

    $route = \Drupal::routeMatch()->getRouteObject();
    $is_admin = \Drupal::service('router.admin_context')->isAdminRoute($route);
    if (!$is_admin) {
      return $element;
    }

    static $init = FALSE;
    if (!isset($element['#format'])) {
      return $element;
    }
    // print_r($element['format']['format']);
    if ($init === FALSE) {
      $config = \Drupal::config('nd_visualshortcodes.settings');
      $element['#attached']['drupalSettings']['nd_visualshortcodes'] = array(
        "autostart" => $config->get("autostart"),
        "formats" => $config->get("formats"),
        "confirm_delete" => $config->get("confirm_delete"),
        "html_default_format" => $config->get("html_default_format"),
      );
      $element['#attached']['library'][] = 'nd_visualshortcodes/shortcodes';
      $init = TRUE;
    }

    if (isset($element['value'])) {
      if (!isset($element['format'])) {
        return $element;
      }
      if (isset($element['summary']) && $element['summary']['#type'] == 'textarea') {
        $element['value'] = TextFormat::load_field($element['value'], $element['format']['format'], TRUE, $element['summary']['#id']);
        $element['summary'] = TextFormat::load_field($element['summary'], $element['format']['format'], FALSE);
      }
      else {
        $element['value'] = TextFormat::load_field($element['value'], $element['format']['format']);
      }
    }
    else {
      $element = TextFormat::load_field($element, $element['#format']);
    }

    return $element;
  }

  public static function load_field($field, $format, $show_toggle = TRUE, $add_fields_to_toggle = FALSE) {
    global $theme;
    static $processed_ids = array();
    $use_ckeditor = FALSE;
    $format_arr = FALSE;

    if (is_array($format)) {
      $format_arr = $format;
      $format = isset($format_arr['#value']) ? $format_arr['#value'] : $format_arr['#default_value'];
    }
    // print_r($format);
    if (!isset($field['#id'])) {
      return $field;
    }

    if (isset($processed_ids[$field['#id']])) {
      return $field;
    }

    if (key_exists('#nd_visualshortcodes', $field) && !$field['#nd_visualshortcodes']) {
      return $field;
    }

    if (isset($field['#access']) && !$field['#access']) {
      return $field;
    }

    if ($field['#id'] == "edit-log") {
      return $field;
    }

    if (isset($field['#attributes']['disabled']) && $field['#attributes']['disabled'] == 'disabled') {
      return $field;
    }


    if (!isset($processed_ids[$field['#id']])) {
      $processed_ids[$field['#id']] = array();
    }

    $textarea_id = $field['#id'];

    $class[] = 'ckeditor-mod';
    $_ckeditor_ids[] = $textarea_id;

    $prefix = '<div class = "nd_visualshortcodes_links_wrap"><a class="nd_visualshortcodes_links btn btn-info btn-sm" href="#" data-disable-text = "' . str_replace("'", '"', t('Disable Visual Shortcodes')) . '" data-enable-text = "' . str_replace("'", '"', t('Enable Visual Shortcodes')) . '" data-id="' . $textarea_id . '" data-format = "' . $format . '">';
    $prefix .= t('Loading...');
    $prefix .= '</a><i class="fa fa-spinner fa-spin"></i></div>';

    $field['#prefix'] = (isset($field['#prefix']) ? $field['#prefix'] : '') . $prefix;
    // dsm($field);
    return $field;
  }
}
