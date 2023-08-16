<?php

namespace Drupal\block_api\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 *
 */
class BlockApiController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    $current_user = \Drupal::currentUser()->getRoles();
    $roles = ' ';
    foreach ($current_user as $element) {
      $roles = $roles . $element . ', ';
    }

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Welcome @roles', ['@roles' => $roles]),
    ];

    return $build;
  }

}
