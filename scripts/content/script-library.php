<?php

/**
 * @file
 * Common code related to drupal content scripts.
 */

use Drupal\node\NodeInterface;

const CMS_MIGRATOR_ID = 1317;

/**
 * Load the latest revision of a node.
 *
 * @param int $nid
 *   The node ID.
 *
 * @return \Drupal\node\NodeInterface
 *   The latest revision of that node.
 */
function get_node_at_latest_revision(int $nid): NodeInterface {
  $entity_type_manager = \Drupal::entityTypeManager();
  $node_storage = $entity_type_manager->getStorage('node');
  $result = $node_storage->loadRevision($node_storage->getLatestRevisionId($nid));
  return $result;
}

/**
 * Returns a serialized form of the node as JSON.
 *
 * @param \Drupal\node\NodeInterface $node
 *   The node to serialize.
 * @param string $message
 *   The log message for the new revision.
 */
function save_node_revision(NodeInterface $node, $message): void {
  $states = [
    'draft',
    'review',
  ];
  $moderation_state = $node->get('moderation_state')->value;
  $node->setNewRevision(TRUE);
  // If draft or review preserve the user from revision, otherwise CMS Migrator.
  $uid = (in_array($moderation_state, $states)) ? $node->getRevisionUserId() : CMS_MIGRATOR_ID;
  $node->setRevisionUserId($uid);
  $node->setChangedTime(time());
  $node->setRevisionCreationTime(time());
  // If draft or review append new log message to previous log message.
  $prefix = (in_array($moderation_state, $states)) ? $node->getRevisionLogMessage() . ' - ' : '';
  $node->setRevisionLogMessage($prefix . $message);
  $node->set('moderation_state', $moderation_state);
  $node->save();
}

/**
 * Callback function to concat node ids with string.
 *
 * @param int $nid
 *   The node id.
 *
 * @return string
 *   The node id concatenated to the end of node_
 */
function _va_gov_stringifynid($nid) {
  return "node_$nid";
}

/**
 * Callback function to concat paragraph ids with string.
 *
 * @param int $pid
 *   The paragraph id.
 *
 * @return string
 *   The paragraph id appended to the end of paragraph_.
 */
function _va_gov_stringifypid($pid) {
  return "paragraph_$pid";
}
