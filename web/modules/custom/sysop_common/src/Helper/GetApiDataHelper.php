<?php

namespace Drupal\sysop_common\Helper;

use Drupal\views\Views;
use Drupal\Component\Serialization\Json;

/**
 * Class GetApiDataHelper.
 */
class GetApiDataHelper {

  /**
   * Function getViewsData($path).
   */
   public static function getViewsData($viewName, $viewId, $id = NULL) {
    $view = Views::getView($viewName);
    $view->setDisplay($viewId);
    if (is_array($id) && !empty($id)) {
      $view->setArguments($id);
      $view->execute();
    }
    elseif (empty($id)) {
      $view->execute();
    }
    else {
      $nid = [$id];
      $view->setArguments($nid);
      $view->execute();
    }
    $view_render = $view->render();
    $result = \Drupal::service('renderer')->render($view_render);
    return Json::decode($result, TRUE);
  }
}
