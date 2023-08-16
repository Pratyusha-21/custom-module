<?php

namespace Drupal\routing_check\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Hello World Module routes.
 */
class RoutingCheckController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function simpleContent() {
    return [
      '#markup' => $this->t('Hello'),
    ];
  }

  /**
   * Checks for custom Route.
   */
  public function customRoute($value) {

    return [
      '#markup' => $this->t('Hello @num', ['@num' => $value]),
    ];
  }

}
