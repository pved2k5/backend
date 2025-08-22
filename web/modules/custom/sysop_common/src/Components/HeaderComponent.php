<?php

namespace Drupal\sysop_common\Components;

use Drupal\Core\Entity\EntityInterface;

include_once dirname(dirname(__DIR__)) . '/constants/Constant.inc';

/**
 * Class HeaderComponent.
 */
class HeaderComponent {

  /**
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An array containing entity values.
   */
  public static function createHeaderComponent($entity) {
    $api = '';
    if($entity->entity->hasField('field_option_list')) {
      switch ($entity->entity->get('field_option_list')->value) {
        case 'default':
          $api = sysop_common_API_MENU . 'main';
          break;

        case 'mega_menu_header':
          $api = sysop_common_API_MENU . 'main-menu,generic-menu,global-navigation';
          break;

        default:
          // $component = 'Components';
      }

      return [
        'Name' => 'header', 
        'Key' => $entity->entity->get('field_option_list')->value, 
        'Api' => 'api/menu_items/main?_format=json'
      ];
    }
    return FALSE;
  }

}
