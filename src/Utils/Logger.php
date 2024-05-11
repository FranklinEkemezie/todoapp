<?php

namespace ToDoApp\Utils;

use ToDoApp\Exceptions\NotFoundException;

class Logger {

  // Logger Levels
  private const DEBUG_LEVEL = 120;
  private const INFO_LEVEL = 130;
  private const NOTICE_LEVEL = 140;
  private const WARNING_LEVEL = 150;
  private const ERROR_LEVEL = 160;
  private const CRITICAL_LEVEL = 170;
  private const ALERT_LEVEL = 180;
  private const EMERGENCY_LEVEL = 190;

  private const LEVEL = array(
    120 => 'DEBUG',
    130 => 'INFO',
    140 => 'NOTICE',
    150 => 'WARNING',
    160 => 'ERROR',
    170 => 'CRITICAL',
    180 => 'ALERT',
    190 => 'EMERGENCY'
  );

  public function __construct(
    private string $name,
    private string $logFilename
  ) {
    
  }

  private function addRecord(
    int $level,
    string $message
  ) {

    $file = fopen($this -> logFilename, "a") or die("Unable to open file: " . $this -> logFilename);
    $log_msg = $this -> getLogMessage($level, $message);

    fwrite($file, $log_msg);

    fclose($file);
  }

  /**
   * Constructs the log message for a log
   * 
   * @param int $level The Logger level
   * @param string $message The message to log
   * 
   * @return string Returns the log message with the given criteria
   * 
   */
  private function getLogMessage(int $level, string $message): string {
    $date = date("D M j G:i:s Y");
    $level_name = self::getLevelName($level);
    $logger_name = $this -> name;

    return "[$date] $logger_name.$level_name $message \n";
  }

  /**
   * Get the name of a Logger level with the Logger level ID
   * 
   * @param int $level A valid integer representing the Logger Level
   * 
   * @return string Retunrs the name of the Logger level
   */
  private static function getLevelName(int $level): string {
    if(isset(self::LEVEL[$level])) {
      return self::LEVEL[$level];
    }

    throw new NotFoundException("Level ID $level not found!");
  }

  /**
   * Gets the name of the logger
   * 
   * @return string Returns the name of the logger
   */
  public function getName(): string {
    return $this -> name;
  }

  /**
   * Sets the log file for the logger to use
   */
  public function setLogFile(string $log_filename) {
    $this -> logFilename = $log_filename;
  }


  /**
   * Logs a message to DEBUG level
   */
  public function debug(string $message) {
    $this -> addRecord(self::DEBUG_LEVEL, $message);
  }

  /**
   * Logs a message to INFO level
   */
  public function info(string $message) {
    $this -> addRecord(self::INFO_LEVEL, $message);
  }

  /**
   * Logs a message to NOTICE level
   */
  public function notice(string $message) {
    $this -> addRecord(self::NOTICE_LEVEL, $message);
  }

  /**
   * Logs a message to WARNING level
   */
  public function warning(string $message) {
    $this -> addRecord(self::WARNING_LEVEL, $message);
  }

  /**
   * Logs a message to ERROR level
   */
  public function error(string $message) {
    $this -> addRecord(self::ERROR_LEVEL, $message);
  }

  /**
   * Logs a message to CRITICAL level
   */
  public function critical(string $message) {
    $this -> addRecord(self::CRITICAL_LEVEL, $message);
  }

  /**
   * Logs a message to ALERT level
   */
  public function alert(string $message) {
    $this -> addRecord(self::ALERT_LEVEL, $message);
  }

  /**
   * Logs a message to EMERGENCY level
   */
  public function emergency(string $message) {
    $this -> addRecord(self::EMERGENCY_LEVEL, $message);
  }

}