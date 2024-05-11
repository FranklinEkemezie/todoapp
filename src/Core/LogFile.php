<?php

namespace ToDoApp\Core;

class LogFile {
  private const LOGFILE_DIR = __DIR__ . "\\..\\..\\var\\log\\";

  const USER = 'user';
  const TEST = 'test';
  const TASK = 'task';
  const DEFAULT = 'todoapp';

  public static function get(string $log_file): string {
    return self::LOGFILE_DIR . $log_file . ".log";
  }

}