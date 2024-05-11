<?php

namespace ToDoApp\Models;

use PDO;

abstract class AbstractModel {

  public function __construct(protected PDO $db) {
    
  }
}