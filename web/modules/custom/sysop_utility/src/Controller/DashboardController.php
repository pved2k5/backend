<?php

namespace Drupal\sysop_utility\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DashboardController
 */
class DashboardController extends ControllerBase {

  /**
   * Dashboard Details.
   *
   * @return array
   *   An Array contains the API Response.
   */
  public function details() {
    $frontPage = \Drupal::configFactory()->get('system.site')->get('page.front');

    $build['user_detail'] = [
      '#type' => 'view',
      '#name' => 'user_admin_people',
      '#display_id' => 'block_1',
      '#prefix' => '<div class="layout-column layout-column--half"><div class="user-account">',
      '#suffix' => '</div>',
    ];

    // $build['recent_global_template'] = [
    //   '#type' => 'view',
    //   '#name' => 'dashboard',
    //   '#display_id' => 'block_2',
    //   '#suffix' => '</div>',
    // ];

    $build['front_page'] = [
      '#type' => 'markup',
      '#markup' => '<div class="front-page-header"><h1>Front Page</h1><div class="front-page"><div class="page-detail"><a href="' . $frontPage . '">Click here</a> to view the front page</div></div></div>',
      '#prefix' => '<div class="layout-column layout-column--half">',
    ];

    // $build['recent_content'] = [
    //   '#type' => 'view',
    //   '#name' => 'dashboard',
    //   '#display_id' => 'block_1',
    //   '#suffix' => '</div>',
    // ];

    $build['#attached']['library'][] = 'sysop_utility/admin_pages';

    return $build;
  }

}
