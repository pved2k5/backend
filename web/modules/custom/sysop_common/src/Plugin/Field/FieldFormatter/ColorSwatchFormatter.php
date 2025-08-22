<?php

namespace Drupal\sysop_common\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Template\Attribute;

/**
 * Plugin implementation of the 'background_color' formatter.
 *
 * @FieldFormatter(
 *   id = "sysop_common_colorswatch_formatter",
 *   label = @Translation("Color Swatch Formatter"),
 *   field_types = {
 *     "sysop_common_colorpicker"
 *   }
 * )
 */
class ColorSwatchFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#theme' => 'color_field_formatter_swatch',
        '#color' => $item->value,
        '#width' => "32px",
        '#height' => "32px",
        '#attributes' => new Attribute([
          'class' => [
            'color_field__swatch',
          ],
        ]),
      ];
    }

    return $elements;
  }

}
