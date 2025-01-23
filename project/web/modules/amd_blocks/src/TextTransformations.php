<?php

declare(strict_types=1);

namespace Drupal\amd_blocks;

use \Drupal\Core\Logger\LoggerChannelFactory;

/**
 * @todo Add class description.
 */
final class TextTransformations {
  /**
   * Logger factory.
   * 
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $logger;

  public function __construct(LoggerChannelFactory $loggerFactory) {
    $this->logger = $loggerFactory;
  }

  public function reverse($text) {
    // \Drupal::logger('amd_blocks')->log();
    // \Drupal::logger('amd_blocks')->error();
    // \Drupal::logger('amd_blocks')->warning('The Text was reversed.');
    $this->logger->get('amd_blocks')->warning('The Text was reversed.');
    
    return strrev($text);
  }
  
  public function uppercase($text) {
    $this->logger->get('amd_blocks')->warning('The Text was transformed to be uppercase.');
    return strtoupper($text);
  }
  
  public function titleCase($text) {
    $this->logger->get('amd_blocks')->warning('The Text was transformed to be title case.');
    return ucfirst($text);
  }

}
