<?php

/**
 * @file
 * Contains \Drupal\modal\Form\TestModalForm.
 */

namespace Drupal\modal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\CssCommand;

/**
 * Class TestModalForm.
 *
 * @package Drupal\modal\Form
 */
class TestModalForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'test_modal_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $form['node_title'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Node`s title'),
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Load'),
      '#ajax' => array(
        'callback' => '::open_modal',
      ),
    );

    $form['#title'] = 'Load node ID';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

  public function open_modal(&$form, FormStateInterface $form_state) {
    $node_title = $form_state->getValue('node_title');
    $query = \Drupal::entityQuery('node')
    ->condition('title', $node_title);
    $entity = $query->execute();
    $title = 'Node ID';
    $key = array_keys($entity);
    $id = !empty($key[0]) ? $key[0] : NULL;
    $response = new AjaxResponse();
    if ($id !== NULL) {
      $content = '<div class="test-popup-content"> Node ID is: ' . $id . '</div>';
      $options = array(
        'dialogClass' => 'popup-dialog-class',
        'width' => '300',
        'height' => '300',
      );
      $response->addCommand(new OpenModalDialogCommand($title, $content, $options));

    }
    else {
      $content = 'Not found record with this title <strong>' . $node_title .'</strong>';
      $response->addCommand(new OpenModalDialogCommand($title, $content));
    }
    return $response;
  }
}
