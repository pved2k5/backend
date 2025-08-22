<?php

namespace Drupal\sysop_common\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'background_color' formatter.
 *
 * @FieldFormatter(
 *   id = "sysop_common_colorpicker_formatter",
 *   label = @Translation("Color Picker Formatter"),
 *   field_types = {
 *     "sysop_common_colorpicker"
 *   }
 * )
 */
class ColorCodeFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => $item->value,
      ];
    }
    return $elements;
  }

}
