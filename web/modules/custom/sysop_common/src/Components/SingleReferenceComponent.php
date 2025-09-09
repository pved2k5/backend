<?php

namespace Drupal\sysop_common\Components;

use Drupal\Core\Entity\EntityInterface;
//use Drupal\sysop_common\Helper\GetApiDataHelper;

//include_once dirname(dirname(__DIR__)) . '/constants/Constant.inc';

/**
 * Class SingleReferenceComponent.
 */
class SingleReferenceComponent {

  /**
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An array containing entity values.
   */
  public static function createSingleReferenceComponent($entity, $type) {
    $nid = '';
    $response = [];
    if($entity->entity->hasField('field_single_content')) {
      foreach ($entity->entity->get('field_single_content') as $v) {
        $nid = $v->entity->id();
      }
    }

    switch ($type) {
      case 'hero_banner':
        $key = 'hero_banner';
        $itemId = 'HeroBanner';
        break;
        
    }

    $endPoint = 'api/getContent/V1/view/' . $itemId . '?_format=json&id=' . $nid;
    $response = [
          'Name' => ucwords(str_replace('_', ' ', $key)),
          'Key' => $key,
          'Api' => $endPoint,
        ] + $response;

    return $response;
  }

}
