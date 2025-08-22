<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Plugin implementation of the 'Title+Description Field' field type.
 *
 * @FieldType(
 *   id = "title_desc_field",
 *   label = @Translation("Group Field"),
 *   description = @Translation("Title & Description Wrapper Field."),
 *   default_widget = "title_desc_field_widget",
 *   default_formatter = "title_desc_field_formatter"
 * )
 */
class TitleDescriptionFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field) {
    return [
      'columns' => [
        'font_color' => [
          'type' => 'varchar',
          'length' => '10',
          'not null' => FALSE,
        ],
        'header' => [
          'type' => 'varchar',
          'length' => '255',
          'not null' => FALSE,
        ],
        'sub_header' => [
          'type' => 'varchar',
          'length' => '255',
          'not null' => FALSE,
        ],
        'description' => [
          'type' => 'text',
          'size' => 'big',
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('header')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['font_color'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Font Color'));

    $properties['header'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Header'));

    $properties['sub_header'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Sub Header'));

    $properties['description'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Description'));

    return $properties;
  }

}
