<?php

namespace Drupal\custom_field\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * This field formatter sets the colors as background colors of a text.
 *
 * @FieldFormatter(
 *   id = "background_color_code",
 *   label = @Translation("Background Color Formatter"),
 *   field_types = {
 *     "rgb"
 *   }
 * )
 */
class BackgroundColorFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    // Checking if the Color field has any value or not.
    if ($items->color_combination) {
      foreach ($items as $item) {
        $color = $item->color_combination;

        // Checking if the colors are in RGB format.
        if (substr($color, 0, 1) !== '#') {
          $color = json_decode($color, TRUE);
          $colors[] = Color::rgbToHex($color);
        }
        else {
          $colors[] = $color;
        }
      }
    }

    return [
      '#theme' => 'custom_field',
      '#dynamic_color' => $colors ?? NULL,
      '#attached' => [
        'library' => [
          'custom_field/custom-field',
        ],
      ],
    ];
  }

}