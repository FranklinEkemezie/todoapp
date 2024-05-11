<?php

namespace ToDoApp\Exceptions;

use Exception;

class NotFoundException extends Exception {
  public function __construct($message = NULL) {
    $message = $message ?: 'Resource not found';

    parent::__construct($message);
  }
}


