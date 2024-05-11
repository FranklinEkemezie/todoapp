<?php

namespace ToDoApp\Exceptions;

use Exception;

class DbException extends Exception {
  public function __construct($message = NULL) {
    $message = $message ?: 'An internal server error occurred!';

    parent::__construct($message);
  }
}