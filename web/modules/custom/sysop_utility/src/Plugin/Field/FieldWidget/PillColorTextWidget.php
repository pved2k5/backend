<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'sysop_utility' widget.
 *
 * @FieldWidget(
 *   id = "pill_color_field_widget",
 *   label = @Translation("Pill Color+Text"),
 *   field_types = {
 *     "title_desc_field"
 *   }
 * )
 */
class PillColorTextWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element += [
      '#type' => 'details',
      '#title' => $this->t('Content'),
      '#open' => TRUE,
    ];

    $element['font_color'] = [
      '#title' => $this->t('Pill Color'),
      '#type' => 'textfield',
      '#size' => 7,
      '#maxlength' => 7,
      '#element_validate' => [
        [$this, 'validate'],
      ],
      '#default_value' => isset($items[$delta]->font_color) ? $items[$delta]->font_color : NULL,
    ];
    $element['header'] = [
      '#title' => $this->t('Label'),
      '#type' => 'textfield',
      '#size' => 32,
      '#maxlength' => 32,
      '#default_value' => isset($items[$delta]->header) ? $items[$delta]->header : NULL,
    ];

    return $element;
  }

  /**
   * Validate the color text field.
   */
  public function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (!empty($value)) {
      if (!preg_match('/^#([a-f0-9]{6})$/iD', strtolower($value))) {
        $form_state->setError($element, $this->t("Color must be a 6-digit hexadecimal value, suitable for CSS."));
      }
    }
  }

}
