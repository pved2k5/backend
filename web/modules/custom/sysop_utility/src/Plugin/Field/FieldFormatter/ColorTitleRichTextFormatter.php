<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'sysop_utility' formatter.
 *
 * @FieldFormatter(
 *   id = "color_title_rich_text_field_formatter",
 *   label = @Translation("Font Color+Title+RichText"),
 *   field_types = {
 *     "title_desc_field"
 *   }
 * )
 */
class ColorTitleRichTextFormatter extends FormatterBase {

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
      $elements[$delta]['description'] = [
        '#type' => 'processed_text',
        '#text' => $item->description,
        '#format' => 'full_html',
      ];
    }
    return $elements;
  }

}
