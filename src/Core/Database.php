<?php

namespace ToDoApp\Core;

use Exception;
use PDO;
use ToDoApp\Exceptions\DbException;

class Database {
  private static ?PDO $db_instance = NULL;

  public static function connect(): PDO {
    $db_config = Config::getInstance() -> get('db');

    $hostname = $db_config['hostname'];
    $db_name = $db_config['dbname'];
    $user = $db_config['user'];
    $password = $db_config['password'];

    return new PDO(
      "mysql:host=$hostname;dbname=$db_name",
      $user,
      $password
    );
  }

  /**
   * Gets an instance of the database connection
   * 
   * @return PDO Return an instance of the database connection 
   */
  public static function getInstance(): PDO {
    if(self::$db_instance === NULL) {
      self::$db_instance = self::connect();
    }

    return self::$db_instance;
  }


  /****************************************
  /*                                      *
  /* Database Functions                   *
  /*                                      *
  /****************************************/

  /**
   * Selects rows from database tables
   * 
   * @param PDO $db_conn Specifies the database connection to use
   * @param string $table The table to select data from
   * @param array $fields An array of field names to retrieve
   * @param ?array $condtions An associative array of the conditions for
   * a row to be selected. The key-value element of the array will demand that
   * the row to be selected must have the field 'key' equal to the corresponding value.
   * @param bool $select_any Specifies whether to select a row which satisfies
   * any of the condition or that which satisfies all the conditions.
   * @param bool $select_all Specifies whether to select all the row or just the first one.
   * Default is FALSE, that is the first row is returned by default
   * 
   * @return array|NULL Returns the selected row. If more than one records is to be selected,
   * an indexed array holding each record as an associative array is returned, otherwise an associative
   * array of the first record is returned.
   */
  public static function select(
    PDO $db_conn,
    string $table,
    array $fields,
    ?array $conditions=NULL,
    bool $select_any=FALSE,
    bool $select_all=FALSE
  ): array|NULL {    
    // Fields array must not be empty: If empty, select all
    $fields_ = count($fields) === 0 ? "*" : join(", ", $fields);

    // Conditions
    $bool_operator = $select_any ? "OR" : "AND";
    $conditions_ = join(" $bool_operator ", array_map(function($value) {
      return "$value = :$value"; // parameterize the columns
    }, array_keys($conditions)));

    $query = "SELECT $fields_ FROM $table WHERE $conditions_";

    try {
      $statement = $db_conn -> prepare($query);
      $statement -> execute($conditions);
  
      $row = $statement -> fetchAll(PDO::FETCH_ASSOC) ?: [];  
    } catch(Exception $e) {
      throw new DbException($e -> getMessage());
    }

    if(count($row) === 0) return NULL; // return NULL if no records is selected

    return $select_all ? $row : $row[0];
  }

  /**
   * Creates a row with the given data
   * 
   * @param PDO $db_conn Specifies the database connection to use
   * @param string $table Specifies the table to insert the row
   * @param array $data The data to be inserted
   * 
   * @return bool Returns TRUE on success or FALSE on failure
   */
  public static function create(
    PDO $db_conn,
    string $table,
    array $data
  ): bool {
    $columns = join(", ", array_keys($data));
    $param_values = join(", ", array_map(function($value) {
      return ":" . $value; // parameterize values
    }, array_keys($data)));

    $query = "INSERT INTO $table ($columns) VALUES ($param_values)";

    try {
      $statement = $db_conn -> prepare($query);
      $is_success = $statement -> execute($data);

      return $is_success;
    } catch(Exception $e) {
      throw new DbException($e -> getMessage());

      return FALSE;
    }
  }

}
