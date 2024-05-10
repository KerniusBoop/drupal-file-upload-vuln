<?php

namespace Drupal\file_upload\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

class FileUploadForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'file_upload_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['upload_item'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload File'),
      '#upload_validators' => [
        'file_validate_extensions' => ['jpg jpeg gif png txt doc docx pdf'],
        'file_validate_size' => [2097152],
      ],
      '#upload_location' => 'public://',
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Upload File'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $file_id = $form_state->getValue(['upload_item', 0]);
    if ($file_id) {
      $file = File::load($file_id);
      if ($file) {
        $file->setPermanent();
        $file->save();
        $this->messenger()->addMessage($this->t('File uploaded successfully.'));
      }
    } else {
      $this->messenger()->addError($this->t('File upload failed.'));
    }
  }
}