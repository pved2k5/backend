<?php


namespace Drupal\sysop_common\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class MenuApiController extends ControllerBase {

  /**
   * Get menu items for a specific menu.
   */
  public function getMenuItems($menu_name) {
    // Load menu items logic here
    $menu_tree = \Drupal::menuTree();
    $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
    $tree = $menu_tree->load($menu_name, $parameters);
    
    // Transform tree to array
    $items = [];
    foreach ($tree as $item) {
      $items[] = [
        'title' => $item->link->getTitle(),
        'url' => $item->link->getUrlObject()->toString(),
        'weight' => $item->link->getWeight(),
      ];
    }
    
    return new JsonResponse($items);
  }
}