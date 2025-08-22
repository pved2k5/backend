<?php

namespace Drupal\sysop_common\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Path\PathValidator;
use Drupal\sysop_common\Helper\GetApiDataHelper;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\Core\State\StateInterface;

/**
 * Common Get Rest Resource.
 *
 * @RestResource(
 *   id = "common_get_rest_resource",
 *   label = @Translation("Common Get Rest Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/getContent/{version}/{entity_type}",
 *    "https://www.drupal.org/link-relations/create" = "/api/getContent"
 *   }
 * )
 */
class CommonGetRestResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * To get value of query parameter from url.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

  /**
   * The EntityTypeManagerInterface service variable.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $_entityTypeManager;

  /**
   * The PathValidator service variable.
   *
   * @var \Drupal\Core\Entity\PathValidator
   */
  protected $_pathValidator;

  /**
   * The state keyvalue collection.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $_state;

  /**
   * Constructs a new CommonGetRestResource object.
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
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   * @param \Symfony\Component\HttpFoundation\Request $current_request
   *   To get value of query parameter from url.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity Manager Instance
   * @param \Drupal\Core\Entity\PathValidator $pathValidator
   *   Path Validator Instance
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
    AccountProxyInterface $current_user,
    Request $current_request,
    EntityTypeManagerInterface $entityTypeManager,
    PathValidator $pathValidator,
    StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
    $this->currentRequest = $current_request;
    $this->_entityTypeManager = $entityTypeManager;
    $this->_pathValidator = $pathValidator;
    $this->_state = $state;
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
      $container->get('entity_type.manager'),
      $container->get('path.validator'),
      $container->get('state')
    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of bundles for specified entity.
   * @param string $version
   *   String Contains the Version Name.
   * @param string $entity_type
   *   String Contains the Entity Type.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($version, $entity_type) { 
    // Constant file included.
    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }
    try {
      // Execute logic based on the type of entity(views/content/custom etc).
      switch ($entity_type) {
        case "node":
         $pageId = $this->currentRequest->get('id'); 
         $urlObject = $this->_pathValidator->getUrlIfValid('/' . $pageId);
         $routeParam = $urlObject ? $urlObject->getrouteParameters() : '';
         $nodeId = NULL;
         $notFoundStatus = FALSE;
         if(empty($urlObject) || empty($routeParam)) {
           $page404 = \Drupal::config('system.site')->get('page.404');
           if(!empty($page404)) {
            $notFound = array_reverse(explode('/', $page404));
            $nodeId = $notFound[0];
            $notFoundStatus = TRUE;
           }
           else {
             return new ResourceResponse([
              'Status' => 'Failure', 
              'StatusCode' => '404',
              'StatusMessage' => $this->t('Requested Resource is not available.')
            ]);
           }
         }
         else {
          $nodeId = $routeParam['node'];
         }

         $resData = $this->_entityTypeManager->getStorage('api_response')->loadByProperties(['template_id' => $nodeId]);
          if (!empty($resData)) {
            foreach ($resData as $ent) {
              $response = unserialize($ent->getSerializeData());
            }
          }
          if (isset($response['Components']) && !empty($response['Components'])) {
            $result = $response;
            $result['NodeId'] = $routeParam['node'];
            $result['UrlAlias'] = $pageId;
            $result['PageLangugage'] = $this->getPageLanguage($nodeId);
          }
          else {
            return new ResourceResponse([
              'Status' => 'Failure', 
              'StatusCode' => '404', 
              'StatusMessage' => $this->t('Requested Resource is not available.')
            ]);
          }
          break;

        case "views":
          $viewId = $this->currentRequest->get('api');
          $displayId = 'rest_export_' . $this->currentRequest->get('display_id');
          $args = str_replace("+", " ", $this->currentRequest->get('id'));
          $result = GetApiDataHelper::getViewsData($viewId, $displayId, [$args]);
          return new ModifiedResourceResponse($result);
          break;

        case "custom":
          // Fetch the name from request url and pass to the call back function.
          $type = $this->currentRequest->get('type');
          switch ($type) {
            case 'roi_form';
              $args = $this->currentRequest->get('id');
              $obj = \Drupal::service('sysop_roiforms.webform');
              $result['Data']['Items'] = $obj->roiFormFieldResponse($args);
              break;
          }
          break;

        case "menu":
          // Fetch the name from request url and pass to the call back function.
          $args = $this->currentRequest->get('id');
          $obj = \Drupal::service('sysop_common.menu_api_response');
          $result = $obj->getMenuDetail(explode(',', $args));
          break;
      }

      if ($result) {
        if (!isset($result['status'])) {
          $result['Status'] = $notFoundStatus ? 'ERROR' : 'SUCCESS';
          $result['StatusCode'] = $notFoundStatus ? 404 : 200;
          $result['StatusMessage'] = $this->t('Successfuly Generated the Response.');
        }

        $response = new ResourceResponse($result);
        $response->addCacheableDependency($result);
        return $response;
      }
      else {
        return new ResourceResponse(['Status' => 'Failure', 'StatusCode' => '404', 'StatusMessage' => $this->t('Requested Resource is not available.')]);
      }
    }
    catch (RequestException $e) {
      $logger->critical('Error: ' . $e->getMessage());
    }
    catch (\Exception $e) {
      $logger->info('Error: ' . $e->getMessage());
    }
  }

  /**
   * Get Page Language.
   *
   * @param int nid
   *  An Integer contains node ID.
   *
   * @return string
   *  String contains the language code.
   */
  public function getPageLanguage(int $nid) {
    $node = $this->_entityTypeManager->getStorage('node')->load($nid);
    if($node->hasField('field_page_language') && !empty($node->get('field_page_language')->value)) {
      return $node->get('field_page_language')->value;
    }
    return '';
  }

  /**
   * Get Global Configuration.
   *
   * @return array
   *  An array contains the script detail
   */
  public function getGlobalConfiguration() {
    $globalData = [
        'script' => [
            [
               "type" => "header_script", 
               "value" => $this->_state->get('adobe_script')
            ],
            [
                "type" => "footer_script", 
                "value" => '<script type="text/javascript">_satellite.pageBottom();</script>'
            ],
          ],
        'frontpage_url' => $this->_state->get('frontend_url')
      ];

    return $globalData;
  }

}
