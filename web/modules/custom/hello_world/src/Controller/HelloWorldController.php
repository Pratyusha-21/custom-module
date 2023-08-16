<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Hello World Module routes.
 */
class HelloWorldController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    $user_name = \Drupal::currentUser()->getDisplayName();
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Hello @user', ['@user' => $user_name]),
    ];

    return $build;
  }

}
