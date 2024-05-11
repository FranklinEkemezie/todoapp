<?php

namespace ToDoApp\Utils;

use ToDoApp\Exceptions\NotFoundException;

class DependencyInjector {
  private array $dependencies = [];

  /**
   * Sets a depenedency injector
   * 
   * @param string $name The name of the dependency
   * @param object $object The dependency
   */
  public function set(string $name, object $dependency) {
    $this -> dependencies[$name] = $dependency;
  }

  public function get(string $name) {
    if(isset($this -> dependencies[$name])) {
      return $this -> dependencies[$name];
    }

    throw new NotFoundException(
      $name . ' dependency not found'
    );
  }
}