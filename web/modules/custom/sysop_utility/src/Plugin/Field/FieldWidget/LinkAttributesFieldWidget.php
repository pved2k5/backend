<?php

namespace Drupal\sysop_utility\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\link\Plugin\Field\FieldWidget\LinkWidget;

/**
 * Plugin implementation of the 'link_attributes_field_widget' widget.
 *
 * @FieldWidget(
 *   id = "link_attributes_field_widget",
 *   label = @Translation("Link Attributes"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class LinkAttributesFieldWidget extends LinkWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $tag_element = parent::formElement($items, $delta, $element, $form, $form_state);
    $item = $items[$delta];
    $options = $item->get('options')->getValue();
    $tag_element['options']['attributes']['class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Analytic Tag'),
      '#default_value' => !empty($options['attributes']['class']) ? $options['attributes']['class'] : '',
      '#description' => $this->t('Add analytic tag to the link.'),
    ];

    return $tag_element;
  }

}
