<?php

namespace Drupal\form\Form;

use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class UserDetailsConfigForm extends ConfigFormBase {

  /**
   *
   */
  public $emailValidator;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['form.settings'];
  }

  /**
   *
   */
  public function __construct(EmailValidatorInterface $email_validator) {
    $this->emailValidator = $email_validator;
  }

  /**
   *
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('email.validator'));
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('form.settings');

    $form['element'] = [
      '#type' => 'markup',
      '#markup' => '<div id="success"></div>',
    ];
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#required' => TRUE,
      '#default_value' => $config->get('first_name'),
      '#maxlength' => 30,
      '#suffix' => '<div class="error" id="first_name"></div>',
    ];
    $form['middle_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Middle Name'),
      '#default_value' => $config->get('middle_name'),
      '#maxlength' => 30,
      '#suffix' => '<div class="error" id="middle_name"></div>',
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#required' => TRUE,
      '#default_value' => $config->get('last_name'),
      '#maxlength' => 30,
      '#suffix' => '<div class="error" id="last_name"></div>',
    ];
    $form['phone_number'] = [
      '#type' => 'number',
      '#title' => $this->t('Phone Number'),
      '#required' => TRUE,
      '#default_value' => $config->get('phone_number'),
      '#maxlength' => 10,
      '#suffix' => '<div class="error" id="phone_number"></div>',
    ];
    $form['email_id'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Id'),
      '#required' => TRUE,
      '#default_value' => $config->get('email_id'),
      '#suffix' => '<div class="error" id="email_id"></div>',
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => t('Gender'),
      '#options' => ['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other', 'Prefer not to say' => 'Prefer not to say'],
      '#default_value' => $config->get('gender'),
      '#required' => TRUE,
    ];
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::submitData',
      ],
    ];
    $form['#attached']['library'][] = 'form/user_details_form';

    return $form;
  }

  /**
   * Checks validity of the data in the form.
   */
  public function submitData(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    $form_field = $form_state->getValues();
    $config = $this->configFactory()->getEditable('form.settings');
    $email_providers = $config->get('email_providers');
    $dot_position = strpos($form_field['email_id'], '@');
    $email = substr($form_field['email_id'], $dot_position);
    $flag = TRUE;
    if (!preg_match("/^[a-zA-z]*$/", $form_field['first_name'])) {
      $ajax_response->addCommand(new HtmlCommand('#first_name', 'First Name is required and only alphabets and whitespace are allowed.'));
      $flag = FALSE;
    }
    else {
      $ajax_response->addCommand(new HtmlCommand('#first_name', ''));
    }
    if (!preg_match("/^[a-zA-z]*$/", $form_field['middle_name'])) {
      $ajax_response->addCommand(new HtmlCommand('#middle_name', 'Only alphabets and whitespace are allowed.'));
      $flag = FALSE;
    }
    else {
      $ajax_response->addCommand(new HtmlCommand('#middle_name', ''));
    }
    if (!preg_match("/^[a-zA-z]*$/", $form_field['last_name'])) {
      $ajax_response->addCommand(new HtmlCommand('#last_name', 'Last Name is required and only alphabets and whitespace are allowed.'));
      $flag = FALSE;
    }
    else {
      $ajax_response->addCommand(new HtmlCommand('#last_name', ''));
    }
    if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $form_field['email_id'])) {
      $ajax_response->addCommand(new HtmlCommand('#email_id', 'Email is not valid.'));
      $flag = FALSE;
    }
    else {
      $ajax_response->addCommand(new HtmlCommand('#email_id', ''));
    }
    if (!in_array($email, $email_providers)) {
      $ajax_response->addCommand(new HtmlCommand('#email_id', 'This domain is not valid'));
    }
    else {
      $ajax_response->addCommand(new HtmlCommand('#email_id', ''));
    }
    if ((!preg_match("/^[0-9]*$/", $form_field['phone_number'])) || !strlen($form_field['phone_number']) == 10) {
      $ajax_response->addCommand(new HtmlCommand('#phone_number', 'Only numeric value is allowed and should contain 10 digits.'));
      $flag = FALSE;
    }
    else {
      $ajax_response->addCommand(new HtmlCommand('#phone_number', ''));
    }
    if ($flag) {
      $config = $this->config('form.settings');
      $config->set('First Name', $form_state->getValue('first_name'));
      $config->set('Middle Name', $form_state->getValue('middle_name'));
      $config->set('Last Name', $form_state->getValue('last_name'));
      $config->set('Phone Number', $form_state->getValue('phone_number'));
      $config->set('Email Id', $form_state->getValue('email_id'));
      $config->set('Gender', $form_state->getValue('gender'));
      $config->save();
      $ajax_response->addCommand(new HtmlCommand('#success', 'Form submitted successfully'));
    }
    return $ajax_response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
