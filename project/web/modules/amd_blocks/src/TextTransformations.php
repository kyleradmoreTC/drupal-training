<?php

declare(strict_types=1);

namespace Drupal\amd_blocks;

/**
 * @todo Add class description.
 */
final class TextTransformations {

  public function reverse($text) {
    // \Drupal::logger('amd_blocks')->log();
    // \Drupal::logger('amd_blocks')->error();
    \Drupal::logger('amd_blocks')->warning('The Text was reversed.');
    return strrev($text);
  }
  
  public function uppercase($text) {
    \Drupal::logger('amd_blocks')->warning('The Text was transformed to be uppercase.');
    return strtoupper($text);
  }
  
  public function titleCase($text) {
    \Drupal::logger('amd_blocks')->warning('The Text was transformed to be title case.');
    return ucfirst($text);
  }

}
