<?php

namespace ToDoApp\Controllers;

use Exception;
use ToDoApp\Core\LogFile;
use ToDoApp\Core\Request;
use ToDoApp\Core\Response;
use ToDoApp\Core\Views;
use ToDoApp\Domain\Task;
use ToDoApp\Domain\User;
use ToDoApp\Exceptions\DbException;
use ToDoApp\Exceptions\NotFoundException;
use ToDoApp\Models\TaskModel;
use ToDoApp\Models\UserModel;
use ToDoApp\Utils\CustomRedirect;
use ToDoApp\Utils\CustomValidator;
use ToDoApp\Utils\DependencyInjector;

class TaskController extends AbstractController {
  public function __construct(
    DependencyInjector $di,
    protected Request $request
  ) {
    parent::__construct($di, $request);

    // Set up the logger
    $this -> logger -> setLogFile(LogFile::get(LogFile::TASK));
  }

  /**
   * Creates a Task
   */
  public function createTask() {
    // Check if page is accessed by POST; if not redirect
    if(!$this -> request -> isPost()) {
      CustomRedirect::redirect("/user/dashboard");
    }

    // Get the form input
    $form_data = $this -> request -> getParams();

    $task_title = $form_data -> getString('task-title');
    $task_description = $form_data -> getString('task-description');
    $status = (int) $form_data -> getString('status');
    $datetime_due = date_format(
      date_create(
        $form_data -> getString('date-due') . " " .
        $form_data -> getString('time-due')
      ),
      "Y-m-d h:ia"
    );
    $priority = (int) $form_data -> getInt('priority');
    $category = $form_data -> getString('category');

    // Sanitize and validate input
    $error_msg = array();

    $task_title_validator = new CustomValidator($task_title, CustomValidator::VALIDATE_NOT_EMPTY);
    $error_msg['task-title'] = !$task_title_validator -> isValid() ? $task_title_validator -> getErrorMsg() : "";

    $task_description_validator = new CustomValidator($task_title, CustomValidator::VALIDATE_NOT_EMPTY);
    $error_msg['task-description'] = !$task_description_validator -> isValid() ? $task_title_validator -> getErrorMsg() : "";

    $any_errors = false; // Checking for any error
    foreach($error_msg as $form_input => $form_error) {
      if(!empty($form_error)) {
        $any_errors = true;
        break;
      }
    }

    if($any_errors) 
      CustomRedirect::redirect("/user/dashboard");

    // Get the user
    $user_id = $this -> request -> getSession() -> getInt(Request::USER_LOGGED_IN);

    $user_model = new UserModel($this -> db);
    $user = $user_model -> getById($user_id);

    // No errors: Try creating a new task
    try {
      $task_model = new TaskModel($this -> db);
      $task_model -> createTask(
        $user,
        $task_title,
        $task_description,
        $status,
        $datetime_due,
        $priority,
        $category
      );

      // Log task created:  Get the task ID of the task
      $last_inserted_id = (int) $this -> db -> lastInsertId();
      $task = $task_model -> getById($last_inserted_id);
      $taskID = $task -> getTaskId();

      $user_email = $user -> getEmail();
      $this -> logger -> info("Task $taskID created successfully for @$user_email");
    } catch (Exception $e) {
      echo "An error occured";
      echo "<br/>";

      echo $e -> getMessage();

      $user_email = $user -> getEmail();
      $this -> logger -> error("An error occurred creating task for $user_email");

      $message = [
        FALSE,
        'An error occurred'
      ];
      return self::prepareResponse(json_encode($message), "application/json");
    }

    // Go back to where the page came
    CustomRedirect::redirect("/user/dashboard");

  }

  /**
   * Gets the task with specified status
   * 
   * @param int $status The status of the tasks
   * 
   * @return Response The response - tasks with the specified tasks
   */
  public function getTasksByStatus(): Response {
    $task_model = new TaskModel($this -> db);

    $user_id = $this -> request -> getSession() -> getInt(Request::USER_LOGGED_IN);
    $status = $this -> request -> getParams() -> getInt('status') ?? NULL;

    switch($status) {
      case 1:
        $tasks = $task_model -> getTasksNotStarted($user_id);
        break;
      case 2:
        $tasks = $task_model -> getTasksInProgress($user_id);
        break;
      case 3:
        $tasks = $task_model -> getTasksCompleted($user_id);
        break;
      default:
        $tasks = $task_model -> getTasks($user_id);
    }

    return self::prepareResponse(json_encode($tasks), 'application/json');
  }

  private function retrieveTaskByTaskId(string $taskID): ?Task {
    try {
      $task_model = new TaskModel($this -> db);
      $task = $task_model -> getByTaskId($taskID);
    } catch (NotFoundException $e) {
      echo "Task $taskID not found!";
    } catch (DbException $e) {
      echo "Something went wrong!";
    } catch (Exception $e) {
      echo "An error occured!";
    }

    return $task;
  }

  public function viewTask(string $id): Response {
    $task = $this -> retrieveTaskByTaskId($id);
    $user_id = $this -> request -> getSession() -> getInt(Request::USER_LOGGED_IN);

    $task_model = new TaskModel($this -> db);
    $user_model = new UserModel($this -> db);
    $user = $user_model -> getById($user_id);
    $no_of_tasks_in_progress = count($task_model -> getTasksInProgress($user_id)); 

    $context = compact('user', 'task', 'no_of_tasks_in_progress');

    return self::prepareResponse(
      $this -> renderView(Views::getUserView(Views::VIEW_TASK), $context)
    );
  }

  public function editTask(string $id): Response {
    $task_model = new TaskModel($this -> db);

    // Setting the Log file
    $this -> logger -> setLogFile(LogFile::get(LogFile::TASK));

    // Getting the form content
    $form_params = $this -> request -> getParams();
    if($form_params -> getString('task-id') === $id) {
      $form_data = array(
        "title" => $form_params -> getString('task-title'),
        "description" => $form_params -> getString('task-description'),
        "status" => $form_params -> getInt('status'),
        "datetime_due" => trim(
          $form_params -> getString('date-due') . " " .
          $form_params -> getString('time-due')
        ),
        "priority" => $form_params -> getInt('priority'),
        "category" => $form_params -> getString('category')
      );
      $form_data = array_filter($form_data, function($value) {
        return $value;
      }, ARRAY_FILTER_USE_BOTH);
    } else {
      $form_data = [];
    }
    
    try {
      $success = $task_model -> updateTask($id, $form_data);
      $content = $success ?
        [TRUE, "Task edited successfully"] : [FALSE, "Something went wrong!"];
    } catch (DbException $e) {
      $this -> logger -> warning("An error occurred while editing the task ($id): " . $e -> getMessage());
      $content = [FALSE, "An error occurred"];
    }
    catch (Exception $e) {
      $this -> logger -> warning("An error occurred while editing the task ($id): " . $e -> getMessage());
      $content = [FALSE, "An error occurred"];
    }

    return self::prepareResponse(
      json_encode($content),
      "application/json"
    );
  }

  public function deleteTask(string $id): Response {
    $task_model = new TaskModel($this -> db);
    $task = $task_model -> getByTaskId($id);

    


    $context = compact('task');

    return self::prepareResponse(
      json_encode($task)
    );
  }

  public function markAsCompleted(string $id): Response {
    $task_model = new TaskModel($this -> db);
    
    try {
      $success = $task_model -> updateTask($id, ["status" => 3]);
      $content = $success ? [TRUE, "Task $id marked as completed"] : [FALSE, "Something went wrong"];
    } catch (DbException $e) {
      $this -> logger -> warning("An error occurred while marking the task ($id) as completed");
      $content = [FALSE, "An error occurred"];
    } catch (Exception $e) {
      $this -> logger -> warning("An error occurred");
      $content = [FALSE, "An error occurred"];
    }

    return self::prepareResponse(
      json_encode($content),
      "application/json"
    );
  }
}
