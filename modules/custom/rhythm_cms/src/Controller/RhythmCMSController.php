<?php

namespace Drupal\rhythm_cms\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\node\Entity\Node;

/**
 * Controller routines for page example routes.
 */
class RhythmCMSController extends ControllerBase {

  public function home_variants($type) {
    $node = Node::load(97);
    $node = \Drupal::entityTypeManager()->getViewBuilder('node')->view($node);
    return $node;
  }

  public function onepage_variants($type) {
    $node = Node::load(102);
    $node = \Drupal::entityTypeManager()->getViewBuilder('node')->view($node);
    return $node;
  }
}

