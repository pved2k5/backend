<?php

namespace Drupal\sysop_common\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Api response entities.
 */
class ApiResponseViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
