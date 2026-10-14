<?php

namespace Drupal\course_hub\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;

/**
 * Reads and creates Course nodes.
 */
class CourseManager {

  public function __construct(protected EntityTypeManagerInterface $entityTypeManager) {}

  /**
   * Returns the newest published courses.
   */
  public function getPublishedCourses(int $limit = 10): array {
    $storage = $this->entityTypeManager->getStorage('node');
    $ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'course')
      ->condition('status', NodeInterface::PUBLISHED)
      ->sort('created', 'DESC')
      ->range(0, $limit)
      ->execute();
    return $storage->loadMultiple($ids);
  }

  /**
   * Counts published courses.
   */
  public function countPublishedCourses(): int {
    return (int) $this->entityTypeManager->getStorage('node')->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'course')
      ->condition('status', NodeInterface::PUBLISHED)
      ->count()
      ->execute();
  }

  /**
   * Checks whether a course with this title already exists.
   */
  public function courseExists(string $title): bool {
    return (bool) $this->entityTypeManager->getStorage('node')->getQuery()
      ->accessCheck(FALSE)
      ->condition('type', 'course')
      ->condition('title', $title)
      ->range(0, 1)
      ->execute();
  }

  /**
   * Creates and saves a Course node.
   */
  public function createCourse(string $title, string $description): NodeInterface {
    $node = $this->entityTypeManager->getStorage('node')->create([
      'type' => 'course',
      'title' => $title,
      'status' => NodeInterface::PUBLISHED,
      'field_description' => [
        'value' => $description,
        'format' => 'basic_html',
      ],
    ]);
    $node->save();
    return $node;
  }

}