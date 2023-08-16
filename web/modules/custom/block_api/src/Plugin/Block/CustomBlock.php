<?php

namespace Drupal\block_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Block to greet 'Hello'.
 *
 * @Block (
 *   id = "custom block",
 *   admin_label = @translation("Custom Block"),
 *   category = @translation("Custom block"),
 * )
 */
class CustomBlock extends BlockBase {

  /**
   * Builds the response.
   */
  public function build() {
    return [
      '#markup' => $this->t('Hello this is a custom block'),
    ];
  }

}
