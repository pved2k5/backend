<?php

namespace Drupal\sysop_common;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Api response entity.
 *
 * @see \Drupal\sysop_common\Entity\ApiResponse.
 */
class ApiResponseAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\sysop_common\Entity\ApiResponseInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished api response entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published api response entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit api response entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete api response entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add api response entities');
  }


}
