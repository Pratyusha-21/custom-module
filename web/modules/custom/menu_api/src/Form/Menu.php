<?php

namespace Drupal\menu_api\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 */
class Menu extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'menu_api_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['menu_api.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('menu_api.settings');

    $form['element'] = [
      '#type' => 'markup',
      '#markup' => '<div id="success"></div>',
    ];
    $form['movie_budget'] = [
      '#type' => 'number',
      '#title' => $this->t('Movie Budget'),
      '#required' => TRUE,
      '#default_value' => $this->configFactory()->getEditable('menu_api.settings')->get('movie_budget'),
      '#maxlength' => 10,
      '#suffix' => '<div class="error" id="movie_budget"></div>',
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('Save this Budget'),
      '#ajax' => [
        'callback' => '::submitData',
      ],
    ];
    $form['#attached']['library'][] = 'menu_api/menu_api_form';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitData(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    $form_field = $form_state->getValues();
    $flag = TRUE;
    if (!preg_match("/^[0-9]*$/", $form_field['movie_budget']) || strlen($form_field['movie_budget']) == 0) {
      $ajax_response->addCommand(new HtmlCommand('#movie_budget', 'Only numeric value is allowed or enter a valid value'));
      $flag = FALSE;
    }
    else {
      $ajax_response->addCommand(new HtmlCommand('#movie_budget', ''));
    }

    if ($flag) {
      $config = $this->configFactory()->getEditable('menu_api.settings');
      $config->set('movie_budget', $form_state->getValue('movie_budget'));
      $config->save();
      $ajax_response->addCommand(new HtmlCommand('#success', t('@budget is now set as movie budget.', ['@budget' => $form_state->getValue('movie_budget')])));
    }

    return $ajax_response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
