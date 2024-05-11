<?php

namespace ToDoApp\Utils;

class FilteredMap {

  public function __construct(private array $map) {
  }

  public function has(string $name) {
    return isset($this -> map[$name]);
  }

  public function get(string $name) {
    return $this -> map[$name] ?? NULL;
  }

  // Filtering data
  public function getInt(string $name): int {
    return (int) $this -> get($name);
  }

  public function getString(string $name, bool $filter = TRUE): string {
    $value = (string) $this -> get($name);
    return $filter ? addslashes($value) : $value;
  }

}