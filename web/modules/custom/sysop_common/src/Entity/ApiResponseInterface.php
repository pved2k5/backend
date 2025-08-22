<?php

namespace Drupal\sysop_common\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Api response entities.
 *
 * @ingroup sysop_common
 */
interface ApiResponseInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Api response name.
   *
   * @return string
   *   Name of the Api response.
   */
  public function getName();

  /**
   * Sets the Api response name.
   *
   * @param string $name
   *   The Api response name.
   *
   * @return \Drupal\sysop_common\Entity\ApiResponseInterface
   *   The called Api response entity.
   */
  public function setName($name);

  /**
   * Gets the Api response creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Api response.
   */
  public function getCreatedTime();

  /**
   * Sets the Api response creation timestamp.
   *
   * @param int $timestamp
   *   The Api response creation timestamp.
   *
   * @return \Drupal\sysop_common\Entity\ApiResponseInterface
   *   The called Api response entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Get Serialize Data.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getSerializeData();

  /**
   * Set Serialize Data.
   *
   * @param string $serialize
   *   The Common entity serialize data.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setSerializeData($serialize);

  /**
   * Get Open Graph Title.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getOgTitle();

  /**
   * Set Open Graph Title.
   *
   * @param string $og_title
   *   The Common entity og_title.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setOgTitle($og_title);

  /**
   * Get Open Graph Description.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getOgDescription();

  /**
   * Set Open Graph Description.
   *
   * @param string $og_description
   *   The Common entity og_description.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setOgDescription($og_description);

  /**
   * Get Open Graph Description.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getKeywords();

  /**
   * Set Open Graph Description.
   *
   * @param string $og_description
   *   The Common entity og_description.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setKeywords($keywords);
  /**
   * Get Open Graph Image.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getOgImage();

  /**
   * Set Open Graph Image.
   *
   * @param string $og_image
   *   The Common entity og_image.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setOgImage($og_image);

  /**
   * Get Open Graph Sub Description.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getOgSubDescription();

  /**
   * Set Open Graph Sub Description.
   *
   * @param string $og_subdescription
   *   The Common entity og_subdescription.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setOgSubDescription($og_subdescription);

  /**
   * Get Open Graph Product Meta.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getOgProductMeta();

  /**
   * Set Open Graph Product Meta.
   *
   * @param string $og_product_meta
   *   The Common entity og_product_meta.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setOgProductMeta($og_product_meta);

  /**
   * Get Open Graph Button Text.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getOgButtonText();

  /**
   * Set Open Graph Button Text.
   *
   * @param string $og_button_text
   *   The Common entity og_button_text.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setOgButtonText($og_button_text);

  /**
   * Get Open Graph Button Url.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getOgButtonUrl();

  /**
   * Set Open Graph Button Url.
   *
   * @param string $og_button_url
   *   The Common entity og_button_url.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setOgButtonUrl($og_button_url);

  /**
   * Get Open Graph Search Index Type.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getSearchIndexType();

  /**
   * Set Open Graph Search Index Type.
   *
   * @param string $search_index_type
   *   The Common entity search_index_type.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setSearchIndexType($search_index_type);

  /**
   * Get Open Graph Search Category.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getCategory();

  /**
   * Set Open Graph Search Category.
   *
   * @param string $category
   *   The Common entity category.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setCategory($category);

  /**
   * Get Published Date.
   *
   * @return string
   *   Value of the Serialize Data.
   */
  public function getPublishedDate();

  /**
   * Set Published Date.
   *
   * @param string $published_date
   *   The Common entity published_date.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setPublishedDate($published_date);

  /**
   * Get Template ID.
   *
   * @return int
   *   Value of the Node ID.
   */
  public function getTemplateId();

  /**
   * Set Template ID.
   *
   * @param template_id $template_id
   *   The Common entity nid.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setTemplateId($template_id);

  /**
   * Get Node ID.
   *
   * @return string
   *   Value of the Template Type.
   */
  public function getTemplateType();

  /**
   * Set Template Type.
   *
   * @param string $template_type
   *   The Common entity template type.
   *
   * @return \Drupal\celcom_entity\Entity\CommonEntityInterface
   *   The called Common entity entity.
   */
  public function setTemplateType($template_type);

}
