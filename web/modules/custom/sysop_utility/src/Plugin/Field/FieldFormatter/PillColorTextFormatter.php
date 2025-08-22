<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'sysop_utility' formatter.
 *
 * @FieldFormatter(
 *   id = "pill_color_field_formatter",
 *   label = @Translation("Pill Color + Text"),
 *   field_types = {
 *     "title_desc_field"
 *   }
 * )
 */
class PillColorTextFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = $tbl = $tbc = [];
    foreach ($items as $delta => $item) {
      $elements[$delta]['font_color'] = [
        '#type' => 'item',
        '#markup' => $item->font_color,
      ];
      $elements[$delta]['header'] = [
        '#type' => 'item',
        '#markup' => $item->header,
      ];
    }
    return $elements;
  }

}
