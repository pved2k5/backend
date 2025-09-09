<?php

namespace Drupal\sysop_roiforms\Services;

use Drupal\webform\WebformSubmissionForm;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Component\Utility\Crypt;
use Drupal\Component\Serialization\Json;

/**
 * Class RoiFormSubmissionServices.
 */
class RoiFormSubmissionServices {

  /**
   * The EntityTypeManagerInterface service variable.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityStorage;

  /**
   * Logger Factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $loggerFactory;

  /**
   * Email Token Service Variable.
   *
   * @var \Drupal\sysop_roiforms\Services\WebformTokenReplaceServices
   */
  protected $emailToken;

  /**
   * Service constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityStorage
   *   Entity for Webform storage.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $loggerFactory
   *   Logger Factory Object.
   * @param \Drupal\sysop_roiforms\Services\WebformTokenReplaceServices $emailToken
   *   Email Token Replace.
   */
  public function __construct(EntityTypeManagerInterface $entityStorage) {
    $this->entityStorage = $entityStorage;
  //  $this->loggerFactory = $loggerFactory->get('sysop_roiforms');
  //  $this->emailToken = $emailToken;
  }

  /**
   * Manage ROI Forms CRUD Operations.
   *
   * @param array $body
   *   An Array Containing Webform Values.
   *
   * @return array
   *   An Array containing JSON Response.
   */
  public function manageRoiFormSubmission(array $body) {

    switch ($body['op']) {
      case 'insertData':
        return $this->insertRoiData($body);

      break;

      // case 'fetchData':
      //   return $this->fetchRoiData($body);

      // break;

      // case 'removeData':
      //   return $this->removeRoiData($body);

      // break;

      // case 'updateData':
      //   return $this->updateRoiData($body);

      // break;

      // case 'confirmationEmail':
      //   return $this->confirmationEmail($body);

      // break;

      default:
        // Add Default item here.
    }

  }

  /**
   * Prepare Webform JSON Response.
   *
   * @param array $body
   *   An Array Containing Webform Values.
   *
   * @return array
   *   An Array containing JSON Response.
   */
  public function insertRoiData(array $body) {
    if (empty($body['webform_id'])) {
      $errors = [
        'error' => [
          'code' => 'Webform Not Present',
        ],
      ];
      return $errors;
    }
    // Convert to webform values format.
    $values = [
      'webform_id' => $body['webform_id'],
      'entity_type' => NULL,
      'entity_id' => NULL,
      'in_draft' => FALSE,
      'uri' => '/webform/' . $body['webform_id'] . '/api',
    ];
    $values['data'] = $body['data'];
    //print_r($values); die();
    unset($values['data']['webform_id'], $values['data']['Operation']);

    $values = array_merge($values, $this->getSerialiseData($values['data']));
    // if (isset($values['data']['email_verification_key'])) {
    //   $values['data']['email_verification_key'] = $this->getRandomKey();
    // }
    // if (isset($values['data']['expiry_date'])) {
    //   $values['data']['expiry_date'] = !empty($values['data']['expiry_date']) ? strtotime(trim($values['data']['expiry_date'])) : strtotime('+3 days');
    // }
    // Check for a valid webform.
    $webform = $this->entityStorage->getStorage('webform')->load($values['webform_id']);
    // Pass here for Hidden Field.
    if (!$webform) {
      $errors = [
        'error' => [
          'message' => 'Invalid webform_id value.',
        ],
      ];
      return $errors;
    }
    // Check webform is open.
    $is_open = WebformSubmissionForm::isOpen($webform);

    if ($is_open === TRUE) {
      // Validate submission.
      $errors = WebformSubmissionForm::validateFormValues($values);
      // Check there are no validation errors.
      if (!empty($errors)) {
        $errors = ['error' => $errors];
        return $errors;
      }
      else {
        // Return submission ID.
        $webform_submission = WebformSubmissionForm::submitFormValues($values);
       // $this->loggerFactory->info('Webform Entry Created with SID: ' . $webform_submission->id());
        $varification = isset($values['data']['email_verification_key']) ? ['email_verification'] : '';
      //  $is_email = isset($values['data']['is_email']) ? TRUE : FALSE;
      //  $is_file_saved = isset($values['data']['is_file_saved']) ? TRUE : FALSE;
        // if ($webform_submission->id() && ($is_email || $is_file_saved)) {
        //   $this->emailToken->prepareEmailTemplate($webform_submission->id(), $body, $varification);
        // }

        return [
          'Id' => $webform_submission->id(),
          'data' => [
            'Id' => $webform_submission->id(),
            'Key' => isset($values['data']['email_verification_key']) ? $values['data']['email_verification_key'] : '',
            'Message' => 'Successfully Created the entry in the System',
          ],
        ];
      }
    }
  }

  /**
   * Validate Webform Key/ID.
   *
   * @param array $arg
   *   An Array contains the Validation parameters.
   *
   * @return bool
   *   TRUE or FALSE Flag.
   */
  // public function isValidVerificationKey(array $arg) {
  //   if ($webform_submission = $this->getSubmissionData($arg['Id'])) {
  //     $data = $webform_submission->getData();
  //     if (!strcmp($data['email_verification_key'], $arg['Key'])) {
  //       if ($data['expiry_date'] > \Drupal::time()->getCurrentTime()) {
  //         return 'Valid';
  //       }
  //       return 'Expired';
  //     }
  //   }
  //   return 'Expired';
  // }

  /**
   * Remove Webform Entry.
   *
   * @param array $arg
   *   An Array contains the Validation parameters.
   *
   * @return bool
   *   TRUE or FALSE Flag.
   */
  // public function fetchRoiData(array $arg) {
  //   try {
  //     if ($webform_submission = $this->getSubmissionData($arg['Id'])) {
  //       $data = $webform_submission->getData();

  //       if (!strcmp($data['email_verification_key'], $arg['email_verification_key'])) {
  //         $res = $this->getSerialiseData(Json::decode($data['json_object']), 'decode');
  //         $res_data = array_merge(['id' => 1, 'createdBy' => 'Portal'], Json::decode($res['data']['json_object']));
  //         return ['Message' => 'Successfully Fetch the Record', 'Id' => $arg['Id'], 'data' => $res_data];
  //       }
  //     }
  //   }
  //   catch (Exception $e) {
  //     $this->loggerFactory->error($e->getMessage());
  //   }
  // }

  /**
   * Webform Submission Entry.
   *
   * @param array $arg
   *   An Array contains the Validation parameters.
   *
   * @return bool
   *   TRUE or FALSE Flag.
   */
  // public function updateRoiData(array $arg) {
  //   try {
  //     if ($webform_submission = $this->getSubmissionData($arg['Id'])) {
  //       $data = $webform_submission->getData();

  //       if (!strcmp($data['email_verification_key'], $arg['email_verification_key'])) {
  //         $data['email_verification_key'] = $this->getRandomKey();
  //         $data['expiry_date'] = strtotime('+3 days');
  //         $webform_submission->setData($data);
  //         $webform_submission->save();
  //         $this->emailToken->prepareEmailTemplate($arg['Id'], $arg, 'email_verification');
  //         return [
  //           'Id' => $arg['Id'],
  //           'data' => [
  //             'Id' => $arg['Id'],
  //             'Key' => isset($data['email_verification_key']) ? $data['email_verification_key'] : '',
  //             'Message' => 'Successfully Updated the entry in the System',
  //           ],
  //         ];
  //       }
  //     }
  //   }
  //   catch (Exception $e) {
  //     $this->loggerFactory->error($e->getMessage());
  //   }
  // }

  /**
   * Remove ROI Submission Entry.
   *
   * @param array $arg
   *   An Array contains the Validation parameters.
   *
   * @return bool
   *   TRUE or FALSE Flag.
   */
  // public function removeRoiData(array $arg) {
  //   try {
  //     if ($webform_submission = $this->getSubmissionData($arg['Id'])) {
  //       $data = $webform_submission->getData();
  //       if (!strcmp($data['email_verification_key'], $arg['email_verification_key'])) {
  //         $webform_submission->delete();
  //         return ['data' => 'Successfully Deleted the Record with ID::' . $arg['Id'], 'Id' => $arg['Id']];
  //       }
  //     }
  //   }
  //   catch (Exception $e) {
  //     $this->loggerFactory->error($e->getMessage());
  //   }
  // }

  /**
   * Remove Webform Entry.
   *
   * @return string
   *   Encrypted Key.
   */
  public function getRandomKey() {
    return Crypt::randomBytesBase64(75);
  }

  /**
   * Remove Webform Entry.
   *
   * @param array $data
   *   An Array Contains the submitted data.
   * @param string $items
   *   String Contains the arg for JSON Object.
   *
   * @return array
   *   An array Contains the formatted data.
   */
  public function getSerialiseData(array $data, $items = NULL) {
    $obj = [];
    $submission_data = $siebal_data = [];
    foreach ($data as $index => $item) {
      $body_data = explode('||', $index);
      $submission_data[trim($body_data[0])] = $item;
      if (isset($body_data[1])) {
        $seibal_data[$body_data[1]] = $item;
      }
    }
    if (!$items) {
      $seibal_data = $data;
    }
    $obj['data'] = $submission_data;
    $obj['data']['json_object'] = Json::encode($seibal_data);
    return $obj;
  }

  /**
   * Fet Webform Submission Data.
   *
   * @param int $sid
   *   An Integer Contains the Submission ID.
   *
   * @return object
   *   Submission Entity Object.
   */
  public function getSubmissionData(int $sid) {
    $entity = $this->entityStorage->getStorage('webform_submission')->load($sid);
    if ($entity) {
      return $entity;
    }
    return FALSE;
  }

  /**
   * Confirmation Email.
   *
   * @param array $body
   *   An Array Containing Body Parameter.
   *
   * @return array
   *   An Array contains Success message.
   */
  // public function confirmationEmail(array $body) {
  //   $this->emailToken->prepareEmailTemplate($body['sid'], $body, ['confirmation', 'registration'], $body['reference_no']);
  //   $webform_submission = $this->entityStorage->getStorage('webform_submission')->load(trim($body['sid']));
  //   $webform_submission->delete();
  //   \Drupal::logger('sysop_roiforms')->info('Successfully Removed the Webform Submission:: ' . $body['sid']);
  //   return ['Id' => $body['sid'], 'data' => 'Successfully Sent the Confirmation Email.'];
  // }

}
