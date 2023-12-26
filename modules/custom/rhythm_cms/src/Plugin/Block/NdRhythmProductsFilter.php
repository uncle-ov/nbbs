<?php

/**
 * @file
 * Contains \Drupal\rhythm_cms\Plugin\Block\NdRhythmProductsFilter.
 */

namespace Drupal\rhythm_cms\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Component\Render\FormattableMarkup;

/**
 * Drupal\Core\Block\BlockBase gives us a very useful set of basic functionality
 * for this configurable block. We can just fill in a few of the blanks with
 * defaultConfiguration(), blockForm(), blockSubmit(), and build().
 *
 * @Block(
 *   id = "nd_rhythm_products_filter",
 *   admin_label = @Translation("Rhythm: Products Filter")
 * )
 */
class NdRhythmProductsFilter extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = [];
    $form['from'] = [
      '#type' => 'textfield',
      '#attributes' => array('placeholder' => t('FROM')),
      '#prefix' => '<div class="row form"><div class="col-xs-6 products-filter-from">',
      '#suffix' => '</div>',
    ];
    $form['to'] = [
      '#type' => 'textfield',
      '#attributes' => array('placeholder' => t('TO')),
      '#prefix' => '<div class="col-xs-6 products-filter-to">',
      '#suffix' => '</div></div>',
    ];
    $markup = new FormattableMarkup(
      '<button class="btn btn-mod btn-medium btn-full btn-round">@text</button>',
      ['@text' => t('Filter')]
    );
    $form['filter'] = [
      '#type' => 'item',
      '#markup' => $markup,
    ];

    return ['#markup' => \Drupal::service('renderer')->render($form)];
  }
}
