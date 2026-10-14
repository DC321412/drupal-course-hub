<?php

namespace Drupal\course_hub\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\course_hub\Service\CourseManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Renders the Course Hub page.
 */
class CourseHubController extends ControllerBase {

  public function __construct(protected CourseManager $courseManager) {}

  public static function create(ContainerInterface $container): static {
    return new static($container->get('course_hub.course_manager'));
  }

  public function page(): array {
    $items = [];
    foreach ($this->courseManager->getPublishedCourses(10) as $course) {
      $items[] = $course->toLink()->toRenderable();
    }

    return [
      'intro' => [
        '#markup' => '<p>' . $this->t('Latest published courses.') . '</p>',
      ],
      'list' => [
        '#theme' => 'item_list',
        '#items' => $items,
        '#empty' => $this->t('No courses yet.'),
        '#cache' => ['tags' => ['node_list:course']],
      ],
    ];
  }

}