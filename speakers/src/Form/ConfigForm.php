<?php

/**
 * @file
 * Contains \Drupal\speakers\Form\ConfigForm.
 */

namespace Drupal\speakers\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigForm
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'speakers_config';
  }
  
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return array('speakers.config');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('speakers.config');

    $form_state->set('old_surname', $config->get('surname'));

    $form['surname'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Surname'),
      '#description' => $this->t('Insert the surname of the speaker'),
      '#required' => TRUE,
      '#default_value' => $config->get('surname'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('speakers.config')
      ->set('surname', $form_state->getValue('surname'))
      ->save();

    $surname = $form_state->get('old_surname');
    Cache::invalidateTags(array("surname:$surname"));

    parent::submitForm($form, $form_state);
  }
  

}
