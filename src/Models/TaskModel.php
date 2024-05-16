<?php

namespace ToDoApp\Models;

use Exception;
use ToDoApp\Domain\Task;
use ToDoApp\Domain\User;
use ToDoApp\Exceptions\DbException;
use ToDoApp\Exceptions\NotFoundException;

class TaskModel extends AbstractModel {

  /**
   * Create a task from a given record (row)
   * 
   * @param array $row The row returned from a database result
   * 
   * @return Task The task created from the row
   */
  private static function createTaskFromRow(array $row): Task {
    return new Task(
      $row['task_id'],
      stripslashes($row['title']),
      stripslashes($row['description']),
      $row['status'],
      $row['priority'],
      $row['category'],
      $row['date_created'],
      $row['datetime_due']
    );
  }

  /**
   * Gets a task using the task ID
   * 
   * @param string $taskId The task ID of the task
   * 
   * @return Task The task with the given Task ID
   */
  public function getByTaskId(string $taskId): Task {
    $query = "SELECT * FROM tasks
    WHERE task_id = :task_id";
    $statement = $this -> db -> prepare($query);

    try {
      $statement -> execute([
        "task_id" => $taskId
      ]);
      $row = $statement -> fetch();

      if(empty($row)) {
        throw new NotFoundException("Task $taskId not found");
      }

      return self::createTaskFromRow($row);
    } catch (Exception $e) {
      throw new DbException($e -> getMessage());
    }
  }

  /**
   * Get a particular task with the specified ID
   * 
   * @param int $id The ID of the task to retrieve
   * 
   * @return Task The task with the given ID
   */
  public function getById(int $id): Task {
    $query = "SELECT * FROM tasks
    WHERE id = :id";
    $statement = $this -> db -> prepare($query);

    try {
      $statement -> execute([
        "id" => $id
      ]);
      $row = $statement -> fetch();

      if(empty($row)) {
        throw new NotFoundException("Task $id not found");
      }

      return self::createTaskFromRow($row);
    } catch (Exception $e) {
      throw new DbException($statement -> errorInfo()[2]);
    }

  }
  
  /**
   * Creates a task with the given information
   * 
   * @param string $title The title of the task
   * @param string $description The description of the task
   * @param string $datetime_due The date-time deadline for the task
   * @param int $priority Priority level of the task
   * @param string $category Category of the task
   * 
   * 
   */
  public function createTask(
    int $user_id,
    string $title,
    string $description,
    int $status,
    string $datetime_due = NULL,
    int $priority = 1,
    string $category = NULL
  ): bool {     
    $query = "INSERT INTO tasks (title, task_id, user_id, description, status, datetime_due, priority, category)
      VALUES (:title, :task_id, :user_id, :description, :status, :datetime_due, :priority, :category)";
    $statement = $this -> db -> prepare($query);

    // Generate the task ID
    $task_id = substr(md5(str_shuffle($title)), 0, 13);

    try {
      return $statement -> execute([
        "title" => $title,
        "task_id" => $task_id,
        "user_id" => $user_id,
        "description" => $description,
        "status" => $status,
        "datetime_due" => $datetime_due,
        "priority" => $priority,
        "category" => $category
      ]);
    } catch (Exception $e) {
      throw new DbException($e -> getMessage() . " on " . $e -> getLine());
    }

    return TRUE;
  }

  /**
   * Updates the details of the task
   * 
   * @param int $id The task ID of the task to edit
   * @param mixed[] $task_details An associative array containing the details of the task to be updated.
   * Acceptable keys are: 'title', 'description', 'status', 'datetime_due', 'priority', 'category'.
   */
  public function updateTask(string $task_id, array $task_details): bool {
    $accept_keys = ['title', 'description', 'status', 'datetime_due', 'priority', 'category'];
    $filtered_task_details = array_filter($task_details,
    function($value, $key) use ($accept_keys) {
      if(in_array($key, $accept_keys)) {
        return $value;
      }
    }, ARRAY_FILTER_USE_BOTH);
    $query_params = join(", ", array_map(
      function($key) {
        return "$key = :$key";
      },
      array_keys($filtered_task_details)
    ));

    $query = "UPDATE tasks
    SET $query_params
    WHERE task_id = :task_id";

    $statement = $this -> db -> prepare($query);
    try {
      $params = array_merge($filtered_task_details, ["task_id" => $task_id]);
      $is_success = $statement -> execute($params);

      return $is_success;
    } catch (Exception $e) {
      throw new DbException($e -> getMessage());

      return false;
    }
  }

  /**
   * Deletes the task
   * 
   * @param string $task_id The task ID of the task
   * @param int $user_id The ID of the user
   * 
   * @return bool Returns TRUE on success, otherwise FALSE.
   */
  public function deleteTask(string $task_id, int $user_id): bool {
    $query = "DELETE FROM tasks ";
    return TRUE; 
  }

  public function getTasks(int $user_id): array {
    $query = "SELECT * FROM tasks WHERE user_id = :user_id";
    $statement = $this -> db -> prepare($query);
    $statement -> execute([
      "user_id" => $user_id
    ]);

    $result = $statement -> fetchAll();

    if(empty($result)) return [];

    $tasks = array();
    foreach($result as $task) {
      $tasks[] = self::createTaskFromRow($task);
    }

    return $tasks;
  }

  /**
   * Gets the task of the user of the specified ID that are not started yet
   * 
   * @param int $user_id The ID of the user
   * 
   * @return Task[] Returns an array of the tasks of the user of the specified ID that are not started yet.
   */
  public function getTasksNotStarted(int $user_id): array {
    $query = "SELECT * FROM tasks WHERE user_id = :user_id AND status = :status";
    $statement = $this -> db -> prepare($query);
    $statement -> execute([
      "user_id" => $user_id,
      "status" => 1
    ]);

    $result = $statement -> fetchAll();

    if(empty($result)) return [];
    
    $tasks = array();
    foreach($result as $task) {
      $tasks[] = self::createTaskFromRow($task);
    }

    return $tasks;
  }

  public function getTasksInProgress(int $user_id): array {
    $query = "SELECT * FROM tasks WHERE user_id = :user_id AND status = :status";
    $statement = $this -> db -> prepare($query);
    $statement -> execute([
      "user_id" => $user_id,
      "status" => 2
    ]);

    $result = $statement -> fetchAll();

    if(empty($result)) return [];

    $tasks = array();
    foreach($result as $task) {
      $tasks[] = self::createTaskFromRow($task);
    }

    return $tasks;
  }

  public function getTasksCompleted(int $user_id): array {
    $query = "SELECT * FROM tasks WHERE user_id = :user_id AND status = :status";
    $statement = $this -> db -> prepare($query);
    $statement -> execute([
      "user_id" => $user_id,
      "status" => 3
    ]);

    $result = $statement -> fetchAll();

    if(empty($result)) return [];

    $tasks = array();
    foreach($result as $task) {
      $tasks[] = self::createTaskFromRow($task);
    }

    return $tasks;
  }

}
