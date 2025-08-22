<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'sysop_utility' formatter.
 *
 * @FieldFormatter(
 *   id = "link_attributes_formatter",
 *   label = @Translation("Link Attribute"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class LinkAttributesFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $options = $item->get('options')->getValue();
      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => trim($options['attributes']['class']),
      ];
    }
    return $elements;
  }

}
