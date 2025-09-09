<?php

namespace Drupal\sysop_common\Components;

use Drupal\Core\Entity\EntityInterface;
//use Drupal\sysop_common\Helper\GetApiDataHelper;
//use Drupal\Core\Entity\EntityTypeManagerInterface;

//include_once dirname(dirname(__DIR__)) . '/constants/Constant.inc';
/**
 * Class MultiReferenceComponent.
 */
class MultiReferenceComponent {

  /**
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An array containing entity values.
   */
  public static function createMultiReferenceComponent($entity, $type) {
    $ids = [];
    $response = [];
    if($entity->entity->hasField('field_multi_content')) {
      foreach ($entity->entity->get('field_multi_content') as $v) {
        $ids[] = $v->entity->id();
      }
    }

    switch ($type) {

      case 'flip_cards':
        $key = 'flip_cards';
        $itemId = 'flipCards';
        break;
    }

    $endPoint = 'api/getContent/V1/view/' . $itemId . '?_format=json&id=' . implode("+", array_filter($ids));
    unset($ids);
    $response = [
          'Name' => ucwords(str_replace('_', ' ', $key)),
          'Key' => $key,
          'Api' => $endPoint,
        ] + $response;

    return $response;
  }

}
