<?php

namespace Drupal\sysop_common\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'background_color_colorpicker' widget.
 *
 * @FieldWidget(
 *   id = "sysop_common_colorpicker_widget",
 *   module = "sysop_common",
 *   label = @Translation("Color Picker"),
 *   field_types = {
 *     "sysop_common_colorpicker"
 *   }
 * )
 */
class ColorPickerWidget extends ColorHexWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $element['value'] += [
      '#suffix' => '<div class="field-colorpicker"></div>',
      '#attributes' => ['class' => ['edit-field-colorpicker']],
      '#attached' => [
        // Add Farbtastic color picker and javascript file to trigger the
        // colorpicker.
        'library' => [
          'core/jquery.farbtastic',
          'sysop_common/colorpicker',
        ],
      ],
    ];

    return $element;
  }

}
