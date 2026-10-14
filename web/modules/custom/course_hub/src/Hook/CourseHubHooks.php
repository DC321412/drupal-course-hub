<?php

namespace Drupal\course_hub\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Hook implementations for the Course Hub module.
 */
class CourseHubHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_form_alter().
   */
  #[Hook('form_alter')]
  public function formAlter(array &$form, FormStateInterface $form_state, string $form_id): void {
    if (in_array($form_id, ['node_course_form', 'node_course_edit_form'], TRUE)) {
      $form['title']['widget'][0]['value']['#description'] = $this->t('Use a clear, descriptive course name (at least 5 characters).');
      $form['#validate'][] = [static::class, 'validateTitle'];
    }
  }

  /**
   * Extra title validation added by hook_form_alter().
   */
  public static function validateTitle(array &$form, FormStateInterface $form_state): void {
    $title = trim((string) $form_state->getValue(['title', 0, 'value']));
    if (mb_strlen($title) < 5) {
      $form_state->setErrorByName('title', new TranslatableMarkup('Course titles must be at least 5 characters long.'));
    }
  }

}