<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'sysop_utility' formatter.
 *
 * @FieldFormatter(
 *   id = "title_sub_color_field_formatter",
 *   label = @Translation("Font Color+Header+SubHeader"),
 *   field_types = {
 *     "title_desc_field"
 *   }
 * )
 */
class TitleSubTitleColorFormatter extends FormatterBase {

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
      $elements[$delta]['sub_header'] = [
        '#type' => 'item',
        '#markup' => $item->sub_header,
      ];
    }
    return $elements;
  }

}
