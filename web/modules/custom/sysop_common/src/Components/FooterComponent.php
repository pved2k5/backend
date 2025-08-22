<?php

namespace Drupal\sysop_common\Components;

use Drupal\Core\Entity\EntityInterface;

include_once dirname(dirname(__DIR__)) . '/constants/Constant.inc';

/**
 * Class FooterComponent.
 */
class FooterComponent {

  /**
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An array containing entity values.
   */
  public static function createFooterComponent($entity) {
    $api = $nid = $key = '';
    $recommender = FALSE;

    if($entity->entity->hasField('field_is_available')) {
      $recommender = $entity->entity->get('field_is_available')->value;
    }
    switch ($entity->entity->get('field_footer')->value) {
      case 'footer':
        foreach ($entity->entity->get('field_entity_ref_single_content') as $v) {
          $nid = $v->entity->id();
        }
        if($nid) {
          $api = 'api/getContent/V1/view/freeText?_format=hal_json&id=' . $nid;
          $key = 'free_text';
        }
        else {
          $api = sysop_common_API_MENU . 'footer';
          $key = 'mini_footer';
        }
        break;

      case 'mini_footer':
        $api = sysop_common_API_MENU . 'footer';
        $key = 'mini_footer';
        break;

      default:
        // $component = 'Components';.
    }

    return [
      'Name' => 'Footer',
      'Key' => 'footer', 
      'Api' => 'api/menu_items/main?_format=json', 
      'IsRecommender' => $recommender,
      'IsContactUs' => \Drupal::state()->get('global_contact_us') ? 'api/getContent/V1/custom?_format=json&type=roi_form&id=' . \Drupal::state()->get('global_contact_us') : '',
      'IsChatbot' => \Drupal::state()->get('chatbot'),
    ];
  }

}
