<?php

namespace Drupal\sysop_common\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'sysop_common_custom_option_widget' widget.
 *
 * @FieldWidget(
 *   id = "sysop_common_custom_option_widget",
 *   module = "sysop_common",
 *   label = @Translation("List Options"),
 *   field_types = {
 *     "list_string"
 *   }
 * )
 */
class CustomOptionListWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    dump($items); die();
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $element += [
      '#type' => 'textfield',
      '#default_value' => $value,
      '#size' => 7,
      '#maxlength' => 7,
      '#element_validate' => [
        [$this, 'validate'],
      ],
    ];
    return ['value' => $element];
  }

  /**
   * Validate the color text field.
   */
  public function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (!empty($value)) {
      if (!preg_match('/^#([a-f0-9]{6})$/iD', strtolower($value))) {
        $form_state->setError($element, $this->t("Color must be a 6-digit hexadecimal value, ex- #333333."));
      }
    }
  }

}
