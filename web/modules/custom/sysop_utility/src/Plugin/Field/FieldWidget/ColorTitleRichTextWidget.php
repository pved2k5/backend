<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'sysop_utility' widget.
 *
 * @FieldWidget(
 *   id = "color_title_rich_text_field_widget",
 *   label = @Translation("Color+Title+RichText"),
 *   field_types = {
 *     "title_desc_field"
 *   }
 * )
 */
class ColorTitleRichTextWidget extends WidgetBase {

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
    $element['description'] = [
      '#title' => $this->t('Sub Header'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#default_value' => isset($items[$delta]->description) ? $items[$delta]->description : NULL,
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
