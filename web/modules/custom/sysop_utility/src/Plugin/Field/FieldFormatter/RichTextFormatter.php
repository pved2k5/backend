<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'sysop_utility' formatter.
 *
 * @FieldFormatter(
 *   id = "description_field_formatter",
 *   label = @Translation("Descrition"),
 *   field_types = {
 *     "title_desc_field"
 *   }
 * )
 */
class RichTextFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = $tbl = $tbc = [];
    foreach ($items as $delta => $item) {
      $elements[$delta]['description'] = [
        '#type' => 'processed_text',
        '#text' => $item->description,
        '#format' => 'full_html',
      ];
    }
    return $elements;
  }

}
