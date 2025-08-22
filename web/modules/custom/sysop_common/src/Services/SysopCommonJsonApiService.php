<?php

namespace Drupal\sysop_common\Services;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\path_alias\AliasManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\metatag\MetatagManager;
use Drupal\metatag\MetatagToken;

/**
 * Class CelcomJsonApiService.
 */
class SysopCommonJsonApiService {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $_currentUser;

  /**
   * The EntityTypeManagerInterface service variable.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $_entityTypeManager;

  /**
   * The AliasManager service variable.
   *
   * @var \Drupal\Core\Path\AliasManager
   */
  protected $_pathAlias;

  /**
   * Logger Factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $_loggerFactory;

  /**
   * Metatag Manager.
   *
   * @var \Drupal\metatag\MetatagManager
   */
  protected $_metatagManager;


  /**
   * Metatag Token.
   *
   * @var \Drupal\metatag\MetatagToken
   */
  protected $_metatagToken;


  /**
   * Constructs Service object.
   *
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   Instance for current user.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity for Entity storage.
   * @param \Drupal\path_alias\AliasManagerInterface $pathAlias
   *   Path alias instance.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $loggerFactory
   *   Logger Factory.
   * @param \\Drupal\metatag\MetatagManager $metatagManager
   *   Metatag Manager.
   * @param \Drupal\metatag\MetatagToken $metatagToken
   *   Metatag Token.
   *
   */
  public function __construct(AccountInterface $currentUser, EntityTypeManagerInterface $entityTypeManager, AliasManagerInterface $pathAlias, LoggerChannelFactory $loggerFactory, MetatagManager $metatagManager, MetatagToken $metatagToken) {
    $this->_currentUser = $currentUser;
    $this->_entityTypeManager = $entityTypeManager;
    $this->_pathAlias = $pathAlias;
    $this->_loggerFactory = $loggerFactory->get('sysop_common');
    $this->_metatagManager = $metatagManager;
    $this->_metatagToken = $metatagToken;
  }

  /**
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An array containing entity values.
   */
  public function createJsonApiResponse(EntityInterface $entity) {
    $components = $response = $metaTagsArray = $og_values = $content = $insights = [];
    $header = $footer = $ogTitle = $ogDescription = $ogImage = '';
    $bundle = ['global_pages'];
    //Header
    if($entity->hasField('field_header')){
      foreach ($entity->get('field_header') as $header) {
        $components['Header'][] = \Drupal\sysop_common\Components\HeaderComponent::createHeaderComponent($header);
      }
    }
    else if(in_array($entity->bundle(), $bundle)) {
        $components['Header'][] = [
          'Name' => "header",
          'Key' => "mega_menu_header",
          'Api' => "api/menu_items/main?_format=json"
        ];
        // $content[] = [
        //   'Name' => "Insights Summery",
        //   'Key' => "insights_summery",
        //   'Api' => "api/getContent/V1/view/insightsSummary?_format=hal_json&id=" . $entity->id()
        // ];
    }
    //Left Sidebar
    // if($entity->hasField('field_left_sidebar')){
    //   foreach ($entity->get('field_left_sidebar') as $left_sidebar) {
    //     $insights['LeftSidebar'][] = \Drupal\sysop_common\Components\SingleReferenceComponent::createSingleReferenceComponent($left_sidebar, $left_sidebar->entity->getType());
    //   }
    // }
    // Extracts all tags of a given entity.
    // And combines them with sitewide, per-entity-type, and per-bundle defaults.
    $tags = $this->_metatagManager->tagsFromEntityWithDefaults($entity);

    // Iterate through the $tags and pass it to Drupal\metatag::replace
    foreach ($tags as $key => $value) {
      $metaTokens =  [];
      $og_tags = ['keywords', 'og_title', 'og_description', 'og_image'];
      $metaTokens['TagType'] =  (strpos($key, 'og_') !== FALSE || strpos($key, 'twitter') !== FALSE) ? 'property' : 'name';
      $metaTokens['TagKey'] = str_replace("_", ":", $key);
      $tag_content = $this->_metatagToken->replace($value, ['node' => $entity]);
      if(in_array($key, $og_tags) && !empty($value)) {
        $og_values[$key] = $tag_content;
      }
      if(strpos($key, 'twitter') !== false) {
        $metaTokens['TagKey'] = ($metaTokens['TagKey'] == 'twitter:cards:type') ? str_replace(":cards:type", ":card", $metaTokens['TagKey']) : str_replace("twitter:cards:", "twitter:", $metaTokens['TagKey']);
      }
      $metaTokens['TagContent'] =  $tag_content;
      array_push($metaTagsArray, $metaTokens);
    }
    // Content.
    if($entity->hasField('field_content')){
      $componentList = $entity->get('field_content');
      foreach ($componentList as $component) {
          switch ($component->entity->getType()) {
            case 'flip_cards':
              $content[] = \Drupal\sysop_common\Components\MultiReferenceComponent::createMultiReferenceComponent($component, $component->entity->getType());
              break;

            case 'hero_banner':
              $content[] = \Drupal\sysop_common\Components\SingleReferenceComponent::createSingleReferenceComponent($component, $component->entity->getType());
              break;

            case 'roi_forms':
              $content[] = \Drupal\sysop_common\Components\RoiFormsComponent::createRoiFormsComponent($component);
              break;

            default:
              // $component = 'Components';.
          }
      }
      if($entity->bundle() == 'insights') {
        $insights['Content'] = $content;
      }
      else {
        $components['Content'] = $content;
      }
    }
    //Right Sidebar
    // if($entity->hasField('field_right_sidebar')){
    //   foreach ($entity->get('field_right_sidebar') as $right_sidebar) {
    //     switch ($right_sidebar->entity->getType()) {
    //       case 'latest_insights':
    //         $insights['RightSidebar'][] = \Drupal\sysop_common\Components\SingleReferenceComponent::createSingleReferenceComponent($right_sidebar, $right_sidebar->entity->getType());
    //         break;

    //       case 'roi_forms':
    //         $insights['RightSidebar'][] = \Drupal\sysop_common\Components\RoiFormsComponent::createRoiFormsComponent($right_sidebar);
    //         break;
    //     }
    //   }
    // }
    // if($entity->bundle() == 'insights') {
    //     $components['Content'][] = [
    //       'Name' => "Insights Pages",
    //       'Key' => "insights_page",
    //       'Api' => \Drupal\Component\Serialization\Json::encode($insights)
    //     ];
    //   }
    //Footer
    if($entity->hasField('field_footer')){
      foreach ($entity->get('field_footer') as $footer) {
        $components['Footer'][] = \Drupal\sysop_common\Components\FooterComponent::createFooterComponent($footer);
      }
    }
    else if(in_array($entity->bundle(), $bundle)) {
        $components['Footer'][] = [
          'Name' => "Footer",
          'Key' => "footer",
          'Api' => "api/menu_items/footer?_format=json",
          'IsRecommender' => null
        ];
    }
     // Metatags
     $response['Components'] = $components; 
     if(in_array($entity->bundle(), $bundle)) {
       $response['MetaTags'] = $metaTagsArray;
     }
     // Get Template Object
     $res_data = $this->_entityTypeManager->getStorage('api_response')->loadByProperties(['template_id' => $entity->id()]);
        if (!empty($res_data) && !$entity->isPublished()) {
          foreach ($res_data as $ent) {
            $ent->delete();
            $this->_loggerFactory->notice('Successfully Removed the Items from INDEX: ' . $entity->id());
          }
        }
        elseif (!empty($res_data)) {
          foreach ($res_data as $ent) {
            $ent->name->setValue($entity->label());
            $ent->url_alias->setValue($this->_getPathAlias($entity));
            $ent->serialize_data->setValue(serialize($response));
            $ent->template_type->setValue($entity->bundle());
            $ent->search_index_type->setValue('bizportal');
            $ent->category->setValue($this->getCategoryName($entity));
            $ent->published_date->setValue($this->getArticlePublishedDate($entity));
            if(in_array($entity->bundle(), $bundle)) {
              foreach($og_values as $k => $v) {
                $ent->$k->setValue($v);
              }
            }
            $ent->save();
            $this->_loggerFactory->notice('Successfully Updated the Response for ID: ' . $entity->id());
          }
        }
        else {
          $data = [
            'name' => $entity->label(),
            'template_id' => $entity->id(),
            'uid' => $this->_currentUser->id(),
            'url_alias' => $this->_getPathAlias($entity),
            'serialize_data' => serialize($response),
            'template_type' => $entity->bundle(),
            'search_index_type' => 'bizportal',
            'category' => $this->getCategoryName($entity),
            'published_date' => $this->getArticlePublishedDate($entity),
          ];
          if(in_array($entity->bundle(), $bundle)) {
            $data = array_merge($data, $og_values);
          }
          $template = $this->_entityTypeManager->getStorage('api_response')->create($data);
          $template->save();
          $this->_loggerFactory->notice('Successfully Saved the Items: ' . $entity->id());
        }

    return $components;
  }

  /**
   *  Get Term Name
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An array containing entity values.
   */
  public function getCategoryName(EntityInterface $entity) {
    if($entity->hasField('field_term_select') && $entity->bundle() == 'insights' ){
      $term = $this->_entityTypeManager->getStorage('taxonomy_term')->load($entity->get('field_term_select')->target_id);
      return $term->getName();
    }
    return '';
  }

  /**
   *  Get Published Date
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An array containing entity values.
   */
  public function getArticlePublishedDate(EntityInterface $entity) {
    if($entity->hasField('field_published_date') && $entity->bundle() == 'insights' ){
       return strtotime($entity->get('field_published_date')->value);
    }
    return $entity->getCreatedTime();
  }

  /**
   * Get Aliased Path.
   *
   * @param array $entity
   *   Object Contains the data.
   *
   * @return string
   *   return path.
   */
  public function _getPathAlias($entity) {
    if($entity->bundle() == 'insights' && $entity->hasField('field_button_primary') ) {
      $path = $this->_getAlias($entity->field_button_primary->uri);
    }
    else {
      $path = substr($this->_pathAlias->getAliasByPath('/node/' . $entity->id()), 1);
    }
    return $path;
  }

  /**
   * Get Path Alias.
   *
   * @param array $path
   *   String Containing the URL.
   *
   * @return string
   *   return matched path.
   */
  public function _getAlias($path) {
    $alias = explode(':', $path);
    if ($alias[0] == 'internal') {
      $path = $alias[1];
    }
    elseif ($alias[0] == 'entity') {
      $alias = explode('/', $alias[1]);
      $path = $this->_pathAlias->getAliasByPath('/node/' . $alias[1]);
    }
    return $path;
  }
  
}
