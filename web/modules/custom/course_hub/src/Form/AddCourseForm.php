<?php

namespace Drupal\course_hub\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\course_hub\Service\CourseManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form to add a Course node.
 */
class AddCourseForm extends FormBase {

  public function __construct(protected CourseManager $courseManager) {}

  public static function create(ContainerInterface $container): static {
    return new static($container->get('course_hub.course_manager'));
  }

  public function getFormId(): string {
    return 'course_hub_add_course_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Course title'),
      '#required' => TRUE,
      '#maxlength' => 255,
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#required' => TRUE,
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create course'),
    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $title = trim($form_state->getValue('title'));

    if (mb_strlen($title) < 5) {
      $form_state->setErrorByName('title', $this->t('The title must be at least 5 characters long.'));
    }
    elseif ($this->courseManager->courseExists($title)) {
      $form_state->setErrorByName('title', $this->t('A course with this title already exists.'));
    }

    if (mb_strlen(trim($form_state->getValue('description'))) < 20) {
      $form_state->setErrorByName('description', $this->t('The description must be at least 20 characters long.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $node = $this->courseManager->createCourse(
      trim($form_state->getValue('title')),
      trim($form_state->getValue('description')),
    );
    $this->messenger()->addStatus($this->t('Course %title was created.', ['%title' => $node->label()]));
    $form_state->setRedirect('course_hub.page');
  }

}