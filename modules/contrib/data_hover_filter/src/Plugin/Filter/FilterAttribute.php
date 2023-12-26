<?php

namespace Drupal\data_hover_filter\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Filter(
 *   id = "filter_attribute",
 *   title = @Translation("Data hover Filter"),
 *   description = @Translation("Adds a data-hover attribute containing the link text to &lt;a&gt; tags."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_IRREVERSIBLE,
 * )
 */
class FilterAttribute extends FilterBase {
  public function process($text, $langcode) {
    $html_dom = Html::load($text);

    $links = $html_dom->getElementsByTagName('a');
    foreach ($links as $link) {
      $link->setAttribute('data-hover', $link->nodeValue);
    }

    $text = Html::serialize($html_dom);
    return new FilterProcessResult($text);
  }

}
