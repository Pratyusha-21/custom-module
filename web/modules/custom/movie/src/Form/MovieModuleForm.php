<?php

namespace Drupal\movie\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Movie Module form.
 *
 * @property \Drupal\movie\MovieModuleInterface $entity
 */
class MovieModuleForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Label for the movie module.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\movie\Entity\MovieModule::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $this->entity->status(),
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $this->entity->get('description'),
      '#description' => $this->t('Description of the movie module.'),
    ];

    $form['year'] = [
      '#type' => 'number',
      '#title' => $this->t('Movie Year'),
      '#required' => TRUE,
      '#default_value' => $this->entity->get('year'),
      '#maxlength' => 4,
    ];
    $form['movie'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      // '#tags' => TRUE,
      '#title' => $this->t('Movie Name'),
      '#required' => TRUE,
      '#default_value' => $this->entity->get('movie') ? $this->entityTypeManager->getStorage('node')->load($this->entity->get('movie')) : '',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => ['movie'],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $message_args = ['%label' => $this->entity->label()];
    $this->entity->save();
    $form_state->setValue('movie', $form_state->getValue('movie'));
    $result = parent::save($form, $form_state);
    $message = $result == SAVED_NEW
      ? $this->t('Created new movie module %label.', $message_args)
      : $this->t('Updated movie module %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
