<?php

namespace Drupal\audit\Event;

use Symfony\COntracts\EventDispatcher\Event;

/**
 * Wraps an incident report event for event subscribers.
 */
class IncidentReport extends Event {
    /**
     * Reporter's Name
     * 
     * @var string
     */
    protected $reporterName;
    /**
     * Reporter's Email
     * 
     * @var string
     */
    protected $reporterEmail;
    /**
     * Deleted Entity
     * 
     * @var int
     */
    protected $entity;
    /**
     * Detailed report
     * 
     * @var string
     */
    protected $report;

    /**
     * Constructs an incident report event object.
     */
    public function __construct($reporterName, $reporterEmail, $entity, $report) {
        $this->reporterName = $reporterName;
        $this->reporterEmail = $reporterEmail;
        $this->entity = $entity;
        $this->report = $report;
    }

    public function getReporterName() {
        return $this->reporterName;
    }

    public function getReporterEmail() {
        return $this->reporterEmail;
    }

    public function GetDeletedEntity() {
        return $this->entity;
    }

    public function GetReport() {
        return $this->report;
    }
}