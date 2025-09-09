<?php

namespace Drupal\sysop_common\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\rest\ModifiedResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\State\StateInterface;
use Drupal\sysop_roiforms\Services\RoiFormSubmissionServices;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "common_post_rest_resource",
 *   label = @Translation("Common Post Rest Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/postContent/{version}/{entity_type}",
 *     "create" = "/api/postContent/{version}/{entity_type}"
 *   }
 * )
 */
class CommonPostRestResource extends ResourceBase {
  
  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $_currentUser;

  /**
   * To get value of query parameter from url.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $_currentRequest;

  /**
   * The state keyvalue collection.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $_state;

  /**
   * The state keyvalue collection.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $webformSubmission;

  /**
   * Constructs a new CommonPostRestResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   A current user instance.
   * @param \Symfony\Component\HttpFoundation\Request $currentRequest
   *   A current requerest info.
   * @param \Drupal\Core\State\StateInterface $settings_page
   *   State Service Object.
   *
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $currentUser,
    Request $currentRequest,
    StateInterface $state,
    RoiFormSubmissionServices $webform_submission) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->_currentUser = $currentUser;
    $this->_currentRequest = $currentRequest;
    $this->_state = $state;
    $this->webformSubmission = $webform_submission;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('sysop_common'),
      $container->get('current_user'),
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('state'),
      $container->get('sysop_roiforms.webform_submission')
    );
  }

  /**
   * Responds to POST requests.
   *  
   * Returns a list of bundles for specified entity.
   *
   * @param mixed $data
   *   Data to create the node.
   *
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function post(array $data = []) {
    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    
    $service_name = $this->_currentRequest->get('name');
   // print_r($service_name); print_r($data);die();
    switch ($service_name) {
      case 'shop_sitemap':
        try {
          if (isset($data['Sitemap']) && !empty($data['Sitemap'])) {
            $existingSitemap = $this->_state->get('custom_sitemap_link');
            if($data['Sync']) {
              $updatedSitemap = array_merge($existingSitemap, $data['Sitemap']);
              $this->_state->set('custom_sitemap_link', $updatedSitemap);
            }
            else {
              unset($existingSitemap[key($data['Sitemap'])]);
              $this->_state->set('custom_sitemap_link', $existingSitemap);
            }
            
            $result['Status'] = 'OK';
            $result['StatusMessage'] = $this->t('Successfully Indexed the Item in Sitemap Queue.');
          }
          else {
            $result['StatusMessage'] = $this->t('No Item index in the Sitemap Queue.');
          }
        }
        catch (RequestException $e) {
          $logger->critical('Error on Sitemap Post Request: ' . $e->getMessage());
        }
        break;

        case 'webform_submission':
        try {
          $result = [];
          //echo 'hhgggg';
         // print_r($data); die();
          if (isset($data['webform_id']) && !empty($data['webform_id'])) {
            $fid = $this->webformSubmission->manageRoiFormSubmission($data);
            if (intval($fid['Id'])) {
              $result['data'] = $fid['data'];
              $result['StatusMessage'] = 'SUCCESS';
            }
            else {
              $result['data'] = $fid;
              $result['StatusMessage'] = 'FAILURE';
            }
          }
          $result['Status'] = 'OKq';
          return new ModifiedResourceResponse($result);
        }
        catch (RequestException $e) {
          \Drupal::logger('roi_forms')->critical('Error on ROI Form Post Request: ' . $e->getMessage());
        }
        break;

    }

    return new ResourceResponse($result);
  }

}
