<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_save",
 *   title = @Translation("Shortcode Save"),
 *   description = @Translation("Shortcode Save"),
 *   icon = "fa fa-h-square",
 * )
 */
class SaveShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
	 $query = \Drupal::database()->select('nd_visualshortcodes_saved', 'n');
		$query->fields('n', ['code'])->condition('id', $attrs['saved']);
		$saved = $query->execute()->fetchField();
	
	 return $saved;
  }

  /**
   * {@inheritdoc}
   */
  public function settings(array $attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $query = \Drupal::database()->select('nd_visualshortcodes_saved', 'n');
    $query->fields('n', ['id', 'name']);
    $saved = $query->execute()->fetchAllKeyed(0, 1);

  	if($saved){
  		$form['saved'] = array(
    		'#title' => t('Saved Shortcodes'),
    		'#type' => 'select',
    		'#options' => $saved,
    		'#default_value' => isset($attrs['saved']) ? $attrs['saved'] : '',
    		'#attributes' => array('class' => array('form-control'))
  	  );
  	  $form['delete']['#markup'] = '<a href = "#" class = "delete-saved-shortcode btn btn-warning">' . t('Delete selected') . '</a>';
  	   
  	}

    return $form;
  }
}