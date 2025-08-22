<?php

namespace Drupal\sysop_common\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Api response entity.
 *
 * @ingroup sysop_common
 *
 * @ContentEntityType(
 *   id = "api_response",
 *   label = @Translation("Api response"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\sysop_common\ApiResponseListBuilder",
 *     "views_data" = "Drupal\sysop_common\Entity\ApiResponseViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\sysop_common\Form\ApiResponseForm",
 *       "add" = "Drupal\sysop_common\Form\ApiResponseForm",
 *       "edit" = "Drupal\sysop_common\Form\ApiResponseForm",
 *       "delete" = "Drupal\sysop_common\Form\ApiResponseDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\sysop_common\ApiResponseHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\sysop_common\ApiResponseAccessControlHandler",
 *   },
 *   base_table = "api_response",
 *   translatable = FALSE,
 *   admin_permission = "administer api response entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/api_response/{api_response}",
 *     "add-form" = "/api_response/add",
 *     "edit-form" = "/api_response/{api_response}/edit",
 *     "delete-form" = "/api_response/{api_response}/delete",
 *     "collection" = "/api_response",
 *   },
 *   field_ui_base_route = "api_response.settings"
 * )
 */
class ApiResponse extends ContentEntityBase implements ApiResponseInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSerializeData() {
    return $this->get('serialize_data')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setSerializeData($serialize) {
    $this->set('serialize_data', $serialize);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOgTitle() {
    return $this->get('og_title')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setOgTitle($og_title) {
    $this->set('og_title', $og_title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOgDescription() {
    return $this->get('og_description')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setOgDescription($og_description) {
    $this->set('og_description', $og_description);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getKeywords() {
    return $this->get('keywords')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setKeywords($keywords) {
    $this->set('keywords', $keywords);
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function getOgImage() {
    return $this->get('og_image')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setOgImage($og_image) {
    $this->set('og_image', $og_image);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOgSubDescription() {
    return $this->get('og_subdescription')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setOgSubDescription($og_subdescription) {
    $this->set('og_subdescription', $og_subdescription);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOgProductMeta() {
    return $this->get('og_product_meta')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setOgProductMeta($og_product_meta) {
    $this->set('og_product_meta', $og_product_meta);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOgButtonText() {
    return $this->get('og_button_text')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setOgButtonText($og_button_text) {
    $this->set('og_button_text', $og_button_text);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOgButtonUrl() {
    return $this->get('og_button_url')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setOgButtonUrl($og_button_url) {
    $this->set('og_button_url', $og_button_url);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSearchIndexType() {
    return $this->get('search_index_type')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setSearchIndexType($search_index_type) {
    $this->set('search_index_type', $search_index_type);
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function getTemplateId() {
    return $this->get('template_id')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setTemplateId($template_id) {
    $this->set('template_id', $template_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTemplateType() {
    return $this->get('template_type')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setTemplateType($template_type) {
    $this->set('template_type', $template_type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCategory() {
    return $this->get('category')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setCategory($category) {
    $this->set('category', $category);
    return $this;
  }

    /**
   * {@inheritdoc}
   */
  public function getPublishedDate() {
    return $this->get('published_date')->value;
  }

   /**
   * {@inheritdoc}
   */
  public function setPublishedDate($published_date) {
    $this->set('published_date', $published_date);
    return $this;
  }   
  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Api response entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Api response entity.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -50,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['serialize_data'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Serialize Data'))
      ->setDescription(t('Enter the Value.'))
      ->setSettings([
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 3,
        'width' => '400px',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textarea',
        'weight' => -49,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['url_alias'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('URL Alias'))
      ->setDescription(t('URL Alias.'))
      ->setSettings(
            [
              'text_processing' => 0,
            ]
        )
      ->setDisplayOptions(
            'view', [
              'label' => 'above',
              'type' => 'text_default',
              'weight' => 4,
              'width' => '400px',
            ]
        )
      ->setDisplayOptions(
            'form', [
              'type' => 'string_textarea',
              'weight' => -48,
            ]
        )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['og_title'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('OG Title'))
      ->setDescription(t('OG Title.'))
      ->setSettings(
            [
              'text_processing' => 0,
            ]
        )
      ->setDisplayOptions(
            'view', [
              'label' => 'above',
              'type' => 'text_default',
              'weight' => 5,
              'width' => '400px',
            ]
        )
      ->setDisplayOptions(
            'form', [
              'type' => 'string_textarea',
              'settings' => ['rows' => 2],
              'weight' => -47,
            ]
        )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['og_description'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('OG Description'))
      ->setDescription(t('OG Description'))
      ->setSettings(
            [
              'text_processing' => 0,
            ]
        )
      ->setDisplayOptions(
            'view', [
              'label' => 'above',
              'type' => 'text_default',
              'weight' => 6,
              'width' => '400px',
            ]
        )
      ->setDisplayOptions(
            'form', [
              'type' => 'string_textarea',
              'settings' => ['rows' => 3],
              'weight' => -46,
            ]
        )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['keywords'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('OG keywords'))
      ->setDescription(t('OG keywords'))
      ->setSettings(
            [
              'text_processing' => 0,
            ]
        )
      ->setDisplayOptions(
            'view', [
              'label' => 'above',
              'type' => 'text_default',
              'weight' => 6,
              'width' => '400px',
            ]
        )
      ->setDisplayOptions(
            'form', [
              'type' => 'string_textarea',
              'settings' => ['rows' => 3],
              'weight' => -46,
            ]
        )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['og_image'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('OG Image'))
      ->setDescription(t('OG Image'))
      ->setSettings(
            [
              'text_processing' => 0,
            ]
        )
      ->setDisplayOptions(
            'view', [
              'label' => 'above',
              'type' => 'text_default',
              'weight' => 6,
              'width' => '400px',
            ]
        )
      ->setDisplayOptions(
            'form', [
              'type' => 'string_textarea',
              'settings' => ['rows' => 2],
              'weight' => -45,
            ]
        )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['og_subdescription'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('OG Sub Description'))
      ->setDescription(t('OG Sub Description'))
      ->setSettings(
            [
              'text_processing' => 0,
            ]
        )
      ->setDisplayOptions(
            'view', [
              'label' => 'above',
              'type' => 'text_default',
              'weight' => 6,
              'width' => '400px',
            ]
        )
      ->setDisplayOptions(
            'form', [
              'type' => 'string_textarea',
              'settings' => ['rows' => 2],
              'weight' => -44,
            ]
        )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['og_product_meta'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('OG Product Meta'))
      ->setDescription(t('OG Product Meta'))
      ->setSettings(
            [
              'text_processing' => 0,
            ]
        )
      ->setDisplayOptions(
            'view', [
              'label' => 'above',
              'type' => 'text_default',
              'weight' => 6,
              'width' => '400px',
            ]
        )
      ->setDisplayOptions(
            'form', [
              'type' => 'string_textarea',
              'settings' => ['rows' => 2],
              'weight' => -43,
            ]
        )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['og_button_text'] = BaseFieldDefinition::create('string')
      ->setLabel(t('OG Button Text'))
      ->setDescription(t('OG Button Text.'))
      ->setSettings([
        'max_length' => 20,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 8,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -42,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['og_button_url'] = BaseFieldDefinition::create('string')
      ->setLabel(t('OG Button Url'))
      ->setDescription(t('OG Button Url.'))
      ->setSettings([
        'max_length' => 20,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 8,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -41,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['search_index_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Search Index Type'))
      ->setDescription(t('Search Index Type.'))
      ->setSettings([
        'max_length' => 20,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 8,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -40,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['template_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Template ID'))
      ->setDescription(t('Template ID.'))
      ->setDisplayOptions(
            'view', [
              'label' => 'above',
              'type' => 'integer',
              'weight' => 7,
            ]
        )
      ->setDisplayOptions(
            'form', [
              'type' => 'integer',
              'weight' => -39,
            ]
        )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['template_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bundle Type'))
      ->setDescription(t('Bundle Type.'))
      ->setSettings([
        'max_length' => 20,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 8,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -38,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['category'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Category'))
      ->setDescription(t('Category.'))
      ->setSettings([
        'max_length' => 64,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 8,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -37,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['published_date'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Published Date'))
      ->setDescription(t('Published Date.'));

    $fields['status']->setDescription(t('A boolean indicating whether the Api response is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -35,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
