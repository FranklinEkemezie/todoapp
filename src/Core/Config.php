<?php

namespace ToDoApp\Core;

use ToDoApp\Exceptions\NotFoundException;

class Config {
  private $data;
  private static ?Config $instance = NULL;

  private const CONFIG_FILENAME = __DIR__ . '/../../config/app.json';

  private function __construct() {
    $config_json = file_get_contents(self::CONFIG_FILENAME);
    $this -> data = json_decode($config_json, TRUE);
  }

  public static function getInstance(): Config {
    if(self::$instance === NULL) {
      self::$instance = new Config;
    }
    return self::$instance;
  }

  public function get($key) {
    if(!isset($this -> data[$key])) {
      throw new NotFoundException("Config key $key not found!");
    }
    return $this -> data[$key];
  }
}





?>