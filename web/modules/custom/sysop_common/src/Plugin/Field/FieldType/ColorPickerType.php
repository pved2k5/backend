<?php

namespace Drupal\sysop_common\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'sysop_common_colorpicker' field type.
 *
 * @FieldType(
 *   id = "sysop_common_colorpicker",
 *   label = @Translation("Color Field"),
 *   module = "sysop_common",
 *   description = @Translation("Colorpicker Widget."),
 *   default_widget = "sysop_common_colorpicker_widget",
 *   default_formatter = "sysop_common_colorpicker_formatter"
 * )
 */
class ColorPickerType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Hex value'));

    return $properties;
  }

}
