<?php

namespace Drupal\course_hub\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\course_hub\Service\CourseManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Shows how many published courses exist.
 */
#[Block(
  id: 'course_hub_count_block',
  admin_label: new TranslatableMarkup('Course count'),
  category: new TranslatableMarkup('Course Hub'),
)]
class CourseCountBlock extends BlockBase implements ContainerFactoryPluginInterface {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected CourseManager $courseManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('course_hub.course_manager'),
    );
  }

  public function build(): array {
    return [
      '#markup' => $this->t('@count courses available', [
        '@count' => $this->courseManager->countPublishedCourses(),
      ]),
    ];
  }

  protected function blockAccess(AccountInterface $account): AccessResultInterface {
    return AccessResult::allowedIfHasPermission($account, 'access course hub');
  }

  public function getCacheTags(): array {
    return Cache::mergeTags(parent::getCacheTags(), ['node_list:course']);
  }

}