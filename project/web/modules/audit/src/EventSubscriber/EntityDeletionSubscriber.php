<?php

declare(strict_types=1);

namespace Drupal\audit\EventSubscriber;

use Drupal\audit\Event\IncidentReport;
use Drupal\audit\Event\IncidentReportEvents;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\core_event_dispatcher\EntityHookEvents;
use Drupal\core_event_dispatcher\Event\Entity\EntityDeleteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @todo Add description for this subscriber.
 */
final class EntityDeletionSubscriber implements EventSubscriberInterface {
  /**
   * Stores the current_user service.
   * 
   * @var Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;

  /**
   * Stores the entity_type.manager service.
   * 
   * @var Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Logger factory.
   * 
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $logger;

  public function __construct($currentUser, $entityTypeManager, $logger) {
    $this->currentUser=$currentUser;
    $this->entityTypeManager=$entityTypeManager;
    $this->logger=$logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      EntityHookEvents::ENTITY_DELETE => ['logDeletion'],
      IncidentReportEvents::NEW_INCIDENT => ['logError'],
    ];
  }

  /**
   * if the entity delete event is triggered, log record.
   */
  public function logDeletion(EntityDeleteEvent $event) {
    $deleted_entity = $event->getEntity();
    $entity_type = $deleted_entity->getEntityTypeId();

    if ($deleted_entity instanceof ConfigEntityInterface) {
      return;
    }

    if ($entity_type == "path_alias") {
      return;
    }

    $data = [
      'label' => $deleted_entity->label(),
      'deleted' => time(),
      'deleted_by' => $this->currentUser->id(),
      'entity_type' => $entity_type,
      'entity_bundle' => $deleted_entity->bundle()
    ];

    if (isset($deleted_entity->created)) {
      $data['deleted_entity_author'] = $deleted_entity->created;
    }

    if (isset($deleted_entity->uid)) {
      $data['deleted_entity_author'] = $deleted_entity->uid;
    }

    if (isset($deleted_entity->changed)) {
      $data['changed'] = $deleted_entity->changed;
    }

    $record = $this->entityTypeManager->getStorage('deletion_record')->create($data);
    $record->save();
  }

  public function logError(IncidentReport $event) {
    $name = $event->getReporterName();
    $email = $event ->getReporterEmail();
    $entity = $event ->getDeletedEntity();
    $report = $event ->getReport();

    $this->logger->get('audit')->alert("New incident report by " . $name . " (" . $email . ") on entity " . $entity . ". Details: " . $report);
    $event->stopPropagation();
  }
}
