<?php

namespace Drupal\sysop_common\Components;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class RoiFormsComponent.
 */
class RoiFormsComponent {

  /**
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An array containing entity values.
   */
  public static function createRoiFormsComponent($entity) {
    $id = $name = $header = $label = $redirect = $delay = $confirmation_message = $selector = $bgImage = '';

    if($entity->entity->hasField('field_header')) {
      $label = $entity->entity->get('field_header')->value;
    }
    if($entity->entity->hasField('field_background_color')) {
      $bgColor = $entity->entity->get('field_background_color')->value;
    }
    
    foreach ($entity->entity->get('field_roi_form') as $v) {
      $id = $v->entity->id();
      $name = $v->entity->label();
    }
    $api = 'api/getContent/V1/custom?_format=json&type=roi_form&id=' . $id;

    return [
      'Name' => $name, 
      'Key' => 'roi_form', 
      'Api' => $api, 
      'Header' => $header, 
      'ProductLabel' => $label, 
      'RedirectUrl' => urldecode($redirect),
      'Delay' => $delay,
      'BackgroundColor' => $bgColor,
      'BackgroundImage' => $bgImage,
      'LabelColor' => $labelColor,
      'IsInlineConfirmationMessage' => $inlineConfirmation,
      'ConfirmationMessage' => $confirmation_message,
      'IdSelector' => $selector,
    ];
  }

    /**
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An array containing entity values.
   */
  public function getRoiFormsComponent(EntityTypeManagerInterface $entityTypeManager, $id) {
    $webform = $entityTypeManager->getStorage('webform')->load($id);
    $elements = $webform->getElementsDecodedAndFlattened();

    foreach($webform->getElementsDecodedAndFlattened() as $i => $section) {
      if(isset($section['#options']) && !is_array($section['#options'])) {
          $elements[$i]['#options'] = $entityTypeManager->getStorage('webform_options')->load($section['#options'])->getOptions();
        }
    }
    return $elements;
  }

}
