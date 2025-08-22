<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'sysop_utility' widget.
 *
 * @FieldWidget(
 *   id = "title_desc_field_widget",
 *   label = @Translation("Title+Description"),
 *   field_types = {
 *     "title_desc_field"
 *   }
 * )
 */
class TitleDescriptionWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element += [
      '#type' => 'details',
      '#title' => $this->t('Content'),
      '#open' => TRUE,
    ];

    $element['header'] = [
      '#title' => $this->t('Label'),
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->header) ? $items[$delta]->header : NULL,
    ];
    $element['description'] = [
      '#title' => $this->t('Description'),
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

}
