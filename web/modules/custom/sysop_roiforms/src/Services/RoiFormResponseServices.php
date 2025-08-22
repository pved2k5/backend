<?php

namespace Drupal\sysop_roiforms\Services;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\path_alias\AliasManagerInterface;
use Drupal\Component\Utility\Html;

/**
 * Class RoiFormResponseServices.
 */
class RoiFormResponseServices {

  /**
   * The EntityTypeManagerInterface service variable.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityStorage;

  /**
   * The AliasManager service variable.
   *
   * @var \Drupal\Core\Path\AliasManager
   */
  protected $pathAlias;

  /**
   * Constructs Connection object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityStorage
   *   Entity for file storage.
   * @param \Drupal\Core\Path\AliasManagerInterface $pathAlias
   *   Aliasmanager.
   */
  public function __construct(EntityTypeManagerInterface $entityStorage, AliasManagerInterface $pathAlias) {
    $this->entityStorage = $entityStorage;
    $this->pathAlias = $pathAlias;
  }

  /**
   * ROI Form Field Response.
   *
   * @param string $type
   *   String contains Webform ID.
   *
   * @return array
   *   An Array containing JSON Response.
   */
  public function roiFormFieldResponse($type) {
    $webform = $this->entityStorage->getStorage('webform')->load($type);

    if ($webform) {
      $fieldDetail = [];
      foreach ($webform->getElementsInitialized() as $section) {
        if ($section['#type'] != 'container') {
          $response = [];
          $response['Title'] = isset($section['#title']) ? $section['#title'] : '';
          foreach ($section as $key => $value) {
            switch (isset($value['#type']) && !empty($value['#type'])) {
              case 'textfield':
              case 'textarea':
                $response['FieldList'][] = $this->getFieldList($value, $key);
                $response['ErrorList'][] = $this->getErrorMessageList($value, $key);
                break;

              case 'url':
                $response['FieldList'][] = $this->getFieldList($value, $key, 'url');
                $response['ErrorList'][] = $this->getErrorMessageList($value, $key);
                break;

              case 'managed_file':
                $response['FieldList'][] = $this->getFieldList($value, $key, 'file');
                $response['ErrorList'][] = $this->getErrorMessageList($value, $key);
                break;

              case 'select':
                $response['FieldList'][] = $this->getFieldList($value, $key, 'options');
                $response['ErrorList'][] = $this->getErrorMessageList($value, $key);
                break;

              case 'webform_actions':
                $response['FieldList'][] = $this->getFieldList($value, $key, 'submit_button');
                $response['ErrorList'][] = $this->getErrorMessageList($value, $key);
                break;

              case 'webform_terms_of_service':
                $response['FieldList'][] = $this->getFieldList($value, $key, 'terms_of_service');
                $response['ErrorList'][] = $this->getErrorMessageList($value, $key);
                break;

              case 'item':
                $response['FieldList'][] = $this->getFieldList($value, $key, 'item');
                break;

              case 'processed_text':
                $response['FieldList'][] = $this->getFieldList($value, $key, 'advanced_text');
                break;

              case 'hidden':
                $response['FieldList'][] = $this->getFieldList($value, $key, 'hidden');
                break;

              case 'webform_buttons':
                $response['FieldList'][] = $this->getFieldList($value, $key, 'button');
                break;

              default:
                // $data = 'data';.
            }

          }
          $response['ErrorList'] = array_values(array_filter($response['ErrorList']));
          $fieldDetail[] = $response;
          unset($response);
        }
      }

    }
    return $fieldDetail;
  }

  /**
   * Prepare Field Response.
   *
   * @param array $items
   *   An array containing field keys.
   * @param string $index
   *   String Contain Fieldname.
   * @param string $type
   *   String contain Type of field.
   *
   * @return array
   *   An Array containing Field JSON Response.
   */
  public function getFieldList(array $items, $index, $type = NULL) {
    $field = [];
    $field['Type'] = isset($items['#type']) ? $items['#type'] : '';
    $field['FieldName'] = isset($index) ? $index : '';
    $field['Label'] = isset($items['#title']) ? $items['#title'] : '';
    $field['TargetField'] = isset($items['#shop_key']) ? $items['#shop_key'] : '';
    $field['SfTargetField'] = isset($items['#sf_key']) ? $items['#sf_key'] : '';
    $field['NetCoreTargetField'] = isset($items['#netcore_key']) ? $items['#netcore_key'] : '';
    $field['ShopTargetField'] = isset($items['#mage_key']) ? $items['#mage_key'] : '';
    $field['PlaceHolder'] = isset($items['#placeholder']) ? $items['#placeholder'] : '';
    $field['Size'] = (isset($items['#title_display']) && !empty($items['#title_display'])) ? 50 : 100;
    $field['DependsOn'] = isset($items['#states']['required']) ? $this->getParentValue($items['#states']['required'], 'parent_name') : '';
    $field['VisibleOn'] = isset($items['#states']['required']) ? $this->getParentValue($items['#states']['required'], 'parent_value') : '';
    if ($type == 'options') {
      $field['Options'] = isset($items['#options']) ? $this->getOptions($items['#options']) : '';
    }
    elseif ($type == 'terms_of_service') {
      $field['Label'] = isset($items['#terms_content']) ? $items['#terms_content'] : '';
      $field['TargetField'] = 'terms_of_service';
    }
    elseif ($type == 'submit_button') {
      $field['Attributes'] = isset($items['#submit__attributes']['class']) ? $items['#submit__attributes']['class'][0] : '';
      $field['Event'] = isset($items['#submit__attributes']['event']) ? $items['#submit__attributes']['event'] : 'submit';
      $field['SubmitLabel'] = isset($items['#submit__label']) ? $items['#submit__label'] : '';
    }
    elseif ($type == 'item') {
      $field['Type'] = 'hiddenField';
      $field['HiddenValue'] = isset($items['#markup']) ? strip_tags($items['#markup']) : '';
      $field['TargetField'] = isset($items['#title']) ? $items['#title'] : '';
       if($index == 'subscriptionemailverified') { $field['IsEmailVerified'] = TRUE; }
    }
    elseif ($type == 'advanced_text') {
      $field['Text'] = isset($items['#text']) ? preg_replace("/\r\n|\r|\n/", '', $items['#text']) : '';
    }
    elseif ($type == 'hidden') {
      $field['Type'] = 'hiddenField';
      $field['HiddenValue'] = isset($items['#default_value']) ? $items['#default_value'] : '';
      $field['TargetField'] = '';
    }
    elseif ($type == 'url') {
      $field['Multiple'] = isset($items['#multiple']) ? $items['#multiple'] : false;
      $field['Tooltip'] = isset($items['#field_prefix']) ? $items['#field_prefix'] : '';
      $field['BasePrice'] = isset($items['#minlength']) ? $items['#minlength'] : 0;
      $field['DiscountedPrice'] = isset($items['#maxlength']) ? $items['#maxlength'] : false;
    }
    elseif ($type == 'file') {
      $field['MaxFilesize'] = isset($items['#max_filesize']) ? $items['#max_filesize'] : 2;
      $field['AllowedFileType'] = isset($items['#file_extensions']) ? $items['#file_extensions'] : 'jpeg png gif pdf docx';
      $field['UploadButtonLabel'] = isset($items['#button__title']) ? $items['#button__title'] : 'Choose File';
    }
    $field['VisibleValue'] = isset($items['#states']['visible']) ? $this->getParentValue($items['#states']['visible'], 'parent_value') : '';
    return $field;
  }

  // Add declaration here!!
  public function getErrorMessageList(array $items, $index) {
    $error = [];
    if(!empty($items['#required']) && $items['#required'] == 1) {
      $error[] = [
          'Key' => 'IsRequired',
          'Value' => 'true',
          'ErrorMessage' => $items['#required_error'],
      ];
    }

    if(isset($items['#pattern']) && !empty($items['#pattern'])) {
      $error[] = [
          'Key' => 'Pattern',
          'Value' => $items['#pattern'],
          'ErrorMessage' => $items['#pattern_error'],
      ];
    }

    if(isset($items['#counter_minimum']) && !empty($items['#counter_minimum'])) {
      $error[] = [
          'Key' => 'MinLength',
          'Value' => $items['#counter_minimum'],
          'ErrorMessage' => $items['#counter_minimum_message'],
      ];
    }

    if(isset($items['#counter_maximum']) && !empty($items['#counter_maximum'])) {
      $error[] = [
          'Key' => 'MaxLength',
          'Value' => $items['#counter_maximum'],
          'ErrorMessage' => $items['#counter_maximum_message'],
      ];
    }
    return !empty($error) ? ['FieldListKey' => $index, 'FieldList' => $error] : [];
  }

  /**
   * Prepare Field Response.
   *
   * @param array $items
   *   An array containing field keys.
   * @param array $visibility
   *   An array containing parent visibility.
   * @param string $type
   *   String contain Type of field.
   *
   * @return array
   *   An Array containing Field JSON Response.
   */
  public function getParentVisibility(array $items, $visibility, $index) {
    $field = [];
    $field['Type'] = isset($items['#type']) ? $items['#type'] : '';
    $field['FieldName'] = isset($index) ? $index : '';
    $field['Label'] = isset($items['#title']) ? $items['#title'] : '';
    $field['Size'] = isset($items['#size']) ? $items['#size'] : '100';
    if ($index == 'captcha') {
      $field['VisibleValue'] = $visibility;
    }
    return $field;
  }

  /**
   * Get Options.
   *
   * @param array $options
   *   An array containing options data.
   *
   * @return array
   *   An Array containing key/value data.
   */
  public function getOptions(array $options) {
    $items = [];
    foreach ($options as $index => $item) {
      $temp = [];
      $temp['Key'] = $index;
      $temp['Value'] = $item;
      $items[] = $temp;
      unset($temp);
    }
    return $items;
  }

  /**
   * Get the Parent Key.
   *
   * @param array $vl
   *   An array containing field keys.
   * @param string $type
   *   String contain Type of field.
   *
   * @return string
   *   String contains the parent key name.
   */
  public function getParentValue(array $vl, $type) {
    if ($type == 'parent_name') {
      $pt = explode('"', key($vl));
      return $pt[1];
    }
    elseif ($type == 'parent_value') {
      if (is_array($vl) && count($vl) > 1) {
        $visible = [];
        foreach ($vl as $val) {
          $itemKey = key($val);
          if (current($val[$itemKey])) {
            $visible[] = current($val[$itemKey]);
          }
        }
        return $visible;
      }
      else {
        $itemKey = key($vl);
        return [current($vl[$itemKey])];
      }
    }
  }

}
