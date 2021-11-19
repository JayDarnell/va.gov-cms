<?php

namespace Drupal\va_gov_vet_center\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;

/**
 * Provides base functionality for the Subscription Builder Queue Workers.
 */
abstract class RequiredServiceBase extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * The node storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * The term storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $termStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    EntityStorageInterface $node_storage,
    EntityStorageInterface $term_storage
  ) {
    $this->nodeStorage = $node_storage;
    $this->termStorage = $term_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('entity_type.manager')->getStorage('node'),
      $container->get('entity_type.manager')->getStorage('taxonomy_term')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    // Use the "cms migrator" user account (1317).
    $author_uid = 1317;
    $vet_center = $this->nodeStorage->load($data->facility_id);
    /** @var \Drupal\node\NodeInterface $vet_center */
    $published = $vet_center->isPublished();
    $section_id = $vet_center->field_administration->target_id;
    $moderation_state = $vet_center->get('moderation_state')->value;

    // Create the new node.
    $vet_center_facility_health_service_node = Node::create([
      'type' => 'vet_center_facility_health_servi',
      'status' => $published,
      'moderation_state' => $moderation_state,
      'uid' => $author_uid,
      'field_administration' => [
        ['target_id' => $section_id],
      ],
      'field_office' => [
        ['target_id' => $data->facility_id],
      ],
      'field_service_name_and_descripti' => [
        ['target_id' => $data->term_id],
      ],
    ]);

    $vet_center_facility_health_service_node->save();

  }

}
