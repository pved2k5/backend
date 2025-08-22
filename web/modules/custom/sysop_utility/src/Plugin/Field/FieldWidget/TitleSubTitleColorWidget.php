<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'sysop_utility' widget.
 *
 * @FieldWidget(
 *   id = "title_sub_color_field_widget",
 *   label = @Translation("Font Color+Title+Description"),
 *   field_types = {
 *     "title_desc_field"
 *   }
 * )
 */
class TitleSubTitleColorWidget extends WidgetBase {

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
      '#title' => $this->t('Font Color'),
      '#type' => 'textfield',
      '#size' => 7,
      '#maxlength' => 7,
      '#element_validate' => [
        [$this, 'validate'],
      ],
      '#default_value' => isset($items[$delta]->font_color) ? $items[$delta]->font_color : NULL,
    ];
    $element['header'] = [
      '#title' => $this->t('Header'),
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->header) ? $items[$delta]->header : NULL,
    ];
    $element['sub_header'] = [
      '#title' => $this->t('Sub Header'),
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->sub_header) ? $items[$delta]->sub_header : NULL,
    ];

    return $element;
  }

  /**
   * Preserve Ritch Text Value.
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $key => $value) {
      $values[$key]['description'] = $value['description']['value'];
    }
    return $values;
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
