<?php

namespace Drupal\sysop_common;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Api response entities.
 *
 * @ingroup sysop_common
 */
class ApiResponseListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Api response ID');
    $header['template_id'] = $this->t('Template ID');
    $header['name'] = $this->t('Name');
    $header['template_type'] = $this->t('Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\sysop_common\Entity\ApiResponse $entity */
    $row['id'] = $entity->id();
    $row['template_id'] = $entity->getTemplateId();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.api_response.edit_form',
      ['api_response' => $entity->id()]
    );
    $row['template_type'] = $entity->getTemplateType();
    return $row + parent::buildRow($entity);
  }

}
