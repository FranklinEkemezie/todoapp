<?php

namespace ToDoApp\Models;

use Exception;
use PDO;
use ToDoApp\Core\Database;
use ToDoApp\Domain\User;
use ToDoApp\Exceptions\DbException;
use ToDoApp\Exceptions\NotFoundException;
use ToDoApp\Utils\PasswordHashing;

/**
 * The Model for the User domain class
 */

class UserModel extends AbstractModel {

  /**
   * Create a user object from a given record (row)
   * 
   * @param array $row The row returned from a database result
   * 
   * @return User The user objet created from the row
   */
  private static function createUserFromRow(array $row): User {
    return new User(
      $row['username'],
      $row['email'],
      $row['password'],
      $row['firstname'],
      $row['lastname']
    );
  }

  /**
   * Gets a user with the given details from the database
   * 
   * @param string $email The email of the user to get
   * @param string $password The password of the user to get
   * 
   * @return User The user with the specified email address and password.
   * Throws an exception if user is not found.
   */
  public function get(string $email, string $password): User {
    // Do some stuff and checks if the user exist
    $row = Database::select(
      $this -> db,
      "users",
      [], // empty rows? return all rows
      ["email" => $email]
    );

    if(empty($row)) {
      throw new NotFoundException("User with email: $email not found");
    }

    if(PasswordHashing::verifyPassword($password, $row['password'])) {
      return self::createUserFromRow($row);
    } else {
      throw new NotFoundException("User with email: $email not found");
    }

    return self::createUserFromRow($row);
  }

  public function getById(int $user_id): User {
    $user_row = Database::select(
      $this -> db,
      "users",
      [], // empty array? return all rows
      ["id" => $user_id]
    );

    if(empty($user_row)) {
      throw new NotFoundException("User ID $user_id not found");
    }

    return self::createUserFromRow($user_row);
  }

  /**
   * Creates a user with the given information
   * 
   * @param string $username The username of the user
   * @param string $email The email of the user
   * @param string $password The password of the user
   * 
   * Inserts the the user details into the database
   * 
   * @return bool Returns TRUE if the operation was successful
   */
  public function create(
    string $username,
    string $email,
    string $password
  ): bool {
    $query = "INSERT INTO users (username, email, password)
      VALUES (:username, :email, :password)";
    $statement = $this -> db -> prepare($query);

    try {
      return $statement -> execute([
        "username" => $username,
        "email" => $email,
        // Hash password here
        "password" => PasswordHashing::hashPassword($password)
      ]);
    } catch (Exception $e) {
      throw new DbException($statement -> errorInfo()[2]);
    }
  }

  public function getId(string $email, string $password): int {
    // Do some stuff and check if the user exist...get the ID
    $row = Database::select($this -> db, "users", ["id", "password"], ["email" => $email]);

    if(empty($row)) {
      throw new NotFoundException("User $email not found!");
    }

    if(PasswordHashing::verifyPassword($password, $row['password'])) {
      return (int) $row['id'];
    } else {
      throw new NotFoundException("User $email not found!");
    }
  }

  public function update(int $id, array $update_info): bool {
    $accept_keys = ['firstname', 'lastname', 'username', 'email', 'dob', 'password', 'photo_id'];

    $filtered_update_info = array_filter($update_info, function($value, $key) use ($accept_keys) {
      if(in_array($key, $accept_keys)) {        
        return $value;
      }
    }, ARRAY_FILTER_USE_BOTH);

    $query_params = join(", ", array_map(
      function($key) {
        return "$key = :$key";
      },
      array_keys($filtered_update_info)
    ));

    $query = "UPDATE users SET $query_params WHERE id = :id";

    try {
      $statement = $this -> db -> prepare($query);
      $params = array_merge($filtered_update_info, ["id" => $id]);

      $success = $statement -> execute($params);
      return $success;

    } catch (Exception $e) {
      throw new DbException($e -> getMessage() . json_encode($update_info));

      return false;
    }
  }

  public function getUserDetails(int $user_id, bool $basic=TRUE): array {
    $user_details = array();

    // Fetch user details from users table
    $query = "SELECT * FROM users WHERE id = :id";
    $statement = $this -> db -> prepare($query);
    $statement -> bindParam(":id", $user_id);

    $statement -> execute();
    $row = $statement -> fetch();

    // Populate user details array
    $user_details = array_merge($user_details, [
      "id" => $row['id'],
      "firstname" => $row['firstname'],
      "lastname" => $row['lastname'],
      "username" => $row['username'],
      "email" => $row['email'],
      "dob" => $row['dob'] ?? "",
      "reg-date" => $row['reg_date'] ?? "",
      "photo-id" => $row['photo_id']
    ]);

    if($basic) return $user_details;

    $query = "SELECT * FROM tasks WHERE user_id = :user_id";
    $statement = $this -> db -> prepare($query);
    $statement -> bindParam(":user_id", $user_id);

    $statement -> execute();
    $rows = $statement -> fetchAll(PDO::FETCH_ASSOC);

    $tasks = array();
    foreach($rows as $task) {
      $task_details = [
        "id" => $task['id'],
        "task-id" => $task['task-id'],
        "title" => $task['title'],
        "description" => $task['description'],
        "status" => $task['details'],
        "created" => $task['date_created'],
        "deadline" => $task['datetime_due'],
        "last-edited" => $task['last-edited'],
        "priority" => $task['priority'],
        "category" => $task['category']
      ];

      array_push($tasks, $task_details);
    }
    $user_details = array_merge($user_details, ["tasks" => $tasks]);

    return $user_details;
  }
}