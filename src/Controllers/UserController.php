<?php

namespace ToDoApp\Controllers;

use Exception;
use ToDoApp\Core\LogFile;
use ToDoApp\Core\Request;
use ToDoApp\Core\Response;
use ToDoApp\Models\UserModel;
use ToDoApp\Utils\CustomRedirect;
use ToDoApp\Core\Views;
use ToDoApp\Exceptions\DbException;
use ToDoApp\Exceptions\NotFoundException;
use ToDoApp\Models\TaskModel;
use ToDoApp\Utils\CustomValidator;
use ToDoApp\Utils\PasswordHashing;

class UserController extends AbstractController {
  /**
   * Handles the login functionality
   */
  public function login(): Response {
    // Check if a user is already logged/authenticated
    if ($this -> request -> isAuthenticated()) {
      CustomRedirect::redirect("/user/dashboard");
    }

    // Set the logger file
    $this -> logger -> setLogFile(LogFile::get(LogFile::USER));

    // Try logging user in
    if ($this -> request -> isGet()) {      
      return self::prepareResponse($this -> renderView(Views::LOGIN, []));
    } elseif ($this -> request -> isPost()) {
      $email = $this -> request -> getParams() -> getString('email');
      $password = $this -> request -> getParams() -> getString('password');

      // Do some processing here
      try {
        $user_model = new UserModel($this -> db);
        $user = $user_model -> get($email, $password);
        $user_id = $user_model -> getId($email, $password);

        // Set the session here: start user session
        $_SESSION[Request::USER_LOGGED_IN] = $user_id;

        // Log 
        $this -> logger -> info("User $email logged in successfully");

        CustomRedirect::redirect("/user/dashboard");
      } catch (NotFoundException $e) {
        $form_error_msg = 'Incorrect email address or password ';
        // $form_error_msg .= $e -> getMessage();
        // Uncomment the line above to add extra info to the error message:
        // Not recommended as it may expose extra, sensitive information to the user


        $this -> logger -> warning("Incorrect email address: $email or password");
      } catch (Exception $e) {
        $form_error_msg = 'An error occurred!';
        // $form_error_msg .= $e -> getMessage();
        // Uncomment the line above to add extra info to the error message:
        // Not recommended as it may expose extra, sensitive information to the user

        $this -> logger -> warning("An error occurred logging $email");
      }

      // If the code reaches here, something wrong happened
      $context = compact('email', 'form_error_msg');
      $content_body = $this -> renderView(Views::LOGIN, $context);

      return self::prepareResponse($content_body);
    }
  }

  /**
   * Handles the signup functionality
   */
  public function signup(): Response {
    $has_completed_first_step = !empty($this -> request -> getSession() -> get(Request::USER_SIGNUP_TRACKED));

    if ($this -> request -> isGet() && !$has_completed_first_step) {
      // User requests for the first sign up page
      return self::prepareResponse($this -> renderView(Views::SIGNUP, []));
    } elseif ($this -> request -> isGet() && $has_completed_first_step) {
      // Render the second sign up page
      $username = $this -> request -> getSession() -> get(Request::USER_SIGNUP_TRACKED)['username'];
      $email = $this -> request -> getSession() -> get(Request::USER_SIGNUP_TRACKED)['email'];

      $context = compact('username', 'email');
      $content_body = $this -> renderView(Views::SIGNUP_2, $context);

      return self::prepareResponse($content_body);
    } elseif ($this -> request -> isPost() && !$has_completed_first_step) {
      // User is submitting the first one: process and return the next one
      $username = $this -> request -> getParams() -> getString('username');
      $email = $this -> request -> getParams() -> getString('email');

      // Do some processing here: Validation...sanitization
      $username_validator = new CustomValidator($username, CustomValidator::VALIDATE_AS_USERNAME);
      $email_validator = new CustomValidator($email, CustomValidator::VALIDATE_AS_EMAIL);

      $form_data_is_valid = $username_validator -> isValid() &&
        $email_validator -> isValid();
      if (!$form_data_is_valid) {
        // Go back to the first one and display the error message
        $username_error_msg = $username_validator -> getErrorMsg();
        $email_error_msg = $email_validator -> getErrorMsg();
        $form_error_msg = 'Invalid arguments! Please try again';

        $context = compact('username', 'email', 'username_error_msg', 'email_error_msg', 'form_error_msg');
        $content_body = $this -> renderView(Views::SIGNUP, $context);

        return self::prepareResponse($content_body);
      } else {
        // Add to the session variable
        $username = $username_validator -> sanitizeInput();
        $email = $email_validator -> sanitizeInput();

        $user_info = compact('username', 'email');
        $_SESSION[Request::USER_SIGNUP_TRACKED] = $user_info;

        $content_body = $this -> renderView(Views::SIGNUP_2, $user_info);
        return self::prepareResponse($content_body);
      }
    } elseif ($this -> request -> isPost() && $has_completed_first_step) {
      // User is submitting the last one: Process and then create the user
      $user = $this -> request -> getSession() -> get(Request::USER_SIGNUP_TRACKED);

      $username = $user['username'];
      $email = $user['email'];
      $password = $this -> request -> getParams() -> getString('password');
      $confirm_password = $this -> request -> getParams() -> getString('confirm-password');

      // Do some processing here: Validation...sanitize data
      $username_validator = new CustomValidator($username, CustomValidator::VALIDATE_AS_USERNAME);
      $email_validator = new CustomValidator($email, CustomValidator::VALIDATE_AS_EMAIL);
      $password_validator = new CustomValidator($password, CustomValidator::VALIDATE_AS_PASSWORD);

      $form_data_is_valid = $password_validator -> isValid() &&
        $password === $confirm_password;

      if (!$form_data_is_valid) {
        // Go back to the second one and display the error message
        $password_error_msg = $password_validator -> getErrorMsg();
        $confirm_password_error_msg = $password !== $confirm_password ? "Passwords do not match!" : "";
        $form_error_msg = "Invalid arguments! Please try again!";

        $context = compact(
          'username',
          'password',
          'confirm_password',
          'password_error_msg',
          'confirm_password_error_msg',
          'form_error_msg'
        );

        return self::prepareResponse($this -> renderView(Views::SIGNUP_2, $context));
      } else {
        // Try creating new user: inserts data to database
        try {
          $user_model = new UserModel($this -> db);
          $user_model -> create(
            $username,
            $email,
            $password
          );
        } catch (Exception $e) {
          // An error occured inserting the data: Go back to the form
          // Display the error message
          $form_error_msg = 'An error occurred!';
          // echo 'Error: ' . $e -> getMessage();

          $context = compact(
            'username',
            'password',
            'confirm_password',
            'form_error_msg'
          );

          return self::prepareResponse($this -> renderView(Views::SIGNUP_2, $context));
        }

        unset($_SESSION[Request::USER_SIGNUP_TRACKED]);

        CustomRedirect::redirect("/login");
      }
    } else {
      // No condition met? Display the first one
      return self::prepareResponse($this -> renderView(Views::SIGNUP, []));
    }
  }

  /**
   * Displays the user dashboard view
   */
  public function showDashboard(): Response {
    $user_id = $this -> request -> getSession() -> getInt(Request::USER_LOGGED_IN);

    // Get the task not started
    $task_model = new TaskModel($this -> db);
    $user_model = new UserModel($this -> db);
    $user = $user_model -> getById($user_id);

    // Task
    $tasks = array();

    $tasks['not_started'] = $task_model -> getTasksNotStarted($user_id);
    $tasks['in_progress'] = $task_model -> getTasksInProgress($user_id);
    $tasks['completed'] = $task_model -> getTasksCompleted($user_id);
        
    $context = compact('user', 'tasks');

    $content_body = $this -> renderView(
      Views::getUserView(Views::USER_DASHBOARD),
      $context
    );

    return self::prepareResponse($content_body);
  }

  /**
   * Gets user details
   */
  public function getUserProfile(): Response {
    $user_id = $this -> request -> getSession() -> getInt(Request::USER_LOGGED_IN);

    $user_model = new UserModel($this -> db);
    $user_details = $user_model -> getUserDetails($user_id);

    // If 'detail' GET parameter is specified, filter
    if($this -> request -> getParams() -> has('details')) {
      $details = $this -> request -> getParams() -> get('details');
      $details = json_decode(stripslashes($details));

      // Filter
      if(is_array($details)) {
        // Filter user details based on the given details
        $filtered_user_details = [];
        foreach($details as $detail) {
          if(key_exists($detail, $user_details)) {
            $filtered_user_details[$detail] = $user_details[$detail];
          }
        }

        $user_details = $filtered_user_details;
      } else {
        $user_details = NULL;
      }
    }

    return self::prepareResponse(json_encode($user_details), "text/json");
  }

  /**
   * Edits the user profile
   */
  public function editProfile() {
    $user_model = new UserModel($this -> db);

    $user_id = $this -> request -> getSession() -> getInt(Request::USER_LOGGED_IN);
    $user = $user_model -> getById($user_id);

    // Set the log file
    $this -> logger -> setLogFile(LogFile::get(LogFile::USER));

    /*------------------------------------------------
      Getting the form content:
      firstname, lastname, username, email, dob
    -------------------------------------------*/
    $form_params = $this -> request -> getParams();
    $form_data = array( // only these fields can be edited
      "firstname" => $form_params -> getString('firstname'),
      "lastname" => $form_params -> getString('lastname'),
      "username" => $form_params -> getString('username'),
      "email" => $form_params -> getString('email'),
      "dob" => $form_params -> getString('dob'),
    );

    /* ----------------------------------------------
      Getting password: Check the password
      The current password must be same as the given by the user before
      a new one
    ------------------------------------------------ */
    $curr_pwd = $form_params -> getString('current-password');
    $new_pwd = $form_params -> getString('new-password');

    if(!empty($curr_pwd)) {
      if(
        PasswordHashing::verifyPassword(
          $curr_pwd,
          $user -> getPassword()
        )
      ) {
        // Add to form data array
        $form_data['password'] = PasswordHashing::hashPassword($new_pwd);
      } else {
        $content = [FALSE, "Enter the correct current password"];
  
        return self::prepareResponse(json_encode($content), "application/json");
      }
    }

    /* ---------------------------------------------
      Profile Picture:
      Check if profile picture is chosen from user's photo gallery or avatar
      Delete old profile picture if existing
    ------------------------------------------------ */
    $profile_imgs_dir = __DIR__ . "/../../app_data/profile_imgs";

    if(isset($_FILES['pfp-photo'])) { // Check if photo was uploaded
      $pfp_photo = $_FILES['pfp-photo'];
      
      if(preg_match("/image\/(jpg|jpeg|png|gif)/", $pfp_photo['type'])) {
        $img_type = explode("image/", $pfp_photo['type'])[1]; // Image type
        $img_id = uniqid();

        $upload_dir = $profile_imgs_dir;
        $upload_path = "$upload_dir/user-pfp-$img_id.$img_type";

        // Upload the photo
        if(move_uploaded_file($pfp_photo['tmp_name'], $upload_path)) {
          // Add to form data
          $form_data['photo_id'] = $img_id;

          // Compress the uploaded photo
          $output_path = "$upload_dir/user-pfp-$img_id.gif";
          try {
            compress_image($upload_path, $output_path, $img_type, "gif");
          } catch (Exception $e) {
            // Something went wrong: prevent updating the user's profile photo
            $form_data['photo_id'] = NULL;

            $content = [FALSE, "Something went wrong! Please try again", json_encode($pfp_photo)];
            return self::prepareResponse(json_encode($content), "application/json");
          } finally {
            // If compression was successful: Delete previously uploaded one
            // If compression was successful: No need, also delete it
            unlink($upload_path); // Deletes the uploaded photo
          }
        }
      } else {
        $content = [FALSE, "File format ({$pfp_photo['type']}) not supported", json_encode($pfp_photo)];

        return self::prepareResponse(json_encode($content), "application/json");
      }
    }
    elseif(
      // Check if the user chose an avatar
      json_decode(file_get_contents("php://input"), true) &&
      key_exists("use-avatar", json_decode(file_get_contents("php://input"), true))
    ) {
      $avatar_id = json_decode(file_get_contents("php://input"), true)["use-avatar"];

      if(preg_match("/avatar_[1-6]$/", $avatar_id)) {
        // Add to form data for updating
        $form_data['photo_id'] = $avatar_id;
      } else {
        $content = [FALSE, "Avatar with ID: $avatar_id does not exist", json_encode(["avatar-id" => $avatar_id])];

        return self::prepareResponse(json_encode($content), "application/json");
      }
    }

    // Check if user has a previous/older profile photo folder: Delete it
    $prev_photo_id = $user_model -> getUserDetails($user_id)['photo-id'];
    if(!is_null($prev_photo_id) && file_exists("$profile_imgs_dir/user-pfp-$prev_photo_id.gif")) {
      // Delete it
      unlink("$profile_imgs_dir/user-pfp-$prev_photo_id.gif");
    }

    /* ----------------------------------------------------------
      Continue processing form data for updating:
      Filter empty, NULL, unset or undefined variables
      Update valid user data
    -------------------------------------------------------------- */
    $form_data = array_filter($form_data, function($value) {
      return $value; // remove undefined, empty or null values
    }, ARRAY_FILTER_USE_BOTH);

    try {
      $success = $user_model -> update($user_id, $form_data);
      $content = $success ?
        [TRUE, "Profile updated successfully!", json_encode($form_data)] : 
        [FALSE, "Something went wrong!"];
    } catch (DbException $e) {
      $user_email = $user -> getEmail();
      $this -> logger -> warning(
        "An error occurred while editing User @ ($user_email) profile: " .
        "Data: [" . implode(", ", $form_data) . "] " .
        "MySQL error msg: " . $e -> getMessage()
      );
      $content = [FALSE, "An error occurred", NULL];
    } catch (Exception $e) {
      $user_email = $user -> getEmail();
      $this -> logger -> warning(
        "An error occurred while editing user @($user_email) profile: " . 
        "Data: [" . implode(", ", $form_data) . "] " .
        "MySQL erorr msg: " . $e -> getMessage()
      );

      $content = [FALSE, "An error occurred", NULL];
    }

    return self::prepareResponse(json_encode($content), "application/json");
  }

  /**
   * Gets the user profile Picture image
   */
  public function getUserProfilePicture(): Response {
    $user_id = $this -> request -> getSession() -> getInt(Request::USER_LOGGED_IN);

    $user_model = new UserModel($this -> db);
    $photo_id = $user_model -> getUserDetails($user_id)['photo-id'];

    header("Content-Type: image/gif");

    $profile_imgs_dir = __DIR__ . "/../../app_data/profile_imgs";
    $avatar_imgs_dir = __DIR__ . "/../../assets/imgs/avatars";
    $avatar_imgs = array(
      "avatar_1" => "basketball",
      "avatar_2" => "bicycle",
      "avatar_3" => "bird",
      "avatar_4" => "icewater",
      "avatar_5" => "panda",
      "avatar_6" => "sunglasses"
    );
    $default_img_path = __DIR__ . "/../../assets/imgs/avatars/pfp_bicycle.png";

    if(!is_null($photo_id) && file_exists("$profile_imgs_dir/user-pfp-$photo_id.gif")) {
      $profile_img = imagecreatefromgif("$profile_imgs_dir/user-pfp-$photo_id.gif");  
    } elseif(!is_null($photo_id) && file_exists("$avatar_imgs_dir/pfp_{$avatar_imgs[$photo_id]}.png")) {
      // Profile photo must probably be an avatar
      $profile_img = imagecreatefrompng("$avatar_imgs_dir/pfp_{$avatar_imgs[$photo_id]}.png");
    } else {
      $profile_img = imagecreatefrompng($default_img_path);
    }

    imagegif($profile_img);

    imagedestroy($profile_img);
    
    return self::prepareResponse("", "image/gif");
  }

  /**
   * Display the User settings
   */
  public function displaySettings(): Response {
    $user_id = $this -> request -> getSession() -> getInt(Request::USER_LOGGED_IN);
    
    $task_model = new TaskModel($this -> db);
    $user_model = new UserModel($this -> db);
    $user = $user_model -> getById($user_id);
    $no_of_tasks_in_progress = count($task_model -> getTasksInProgress($user_id)); 

    $context = compact('user', 'no_of_tasks_in_progress');
    $content_body = $this -> renderView(
      Views::getUserView(Views::USER_SETTINGS),
      $context
    );

    return self::prepareResponse($content_body);
  }

  public function getSettingTabContent(string $tab): Response {
    $user_id = $this -> request -> getSession() -> getInt(Request::USER_LOGGED_IN);

    $user_model = new UserModel($this -> db);
    $user = $user_model -> getById($user_id);
    $user_details = $user_model -> getUserDetails($user_id);

    $context = compact('user', 'tab', 'user_details');

    switch($tab) {
      case 'profile':
        $content_body = $this -> renderView(
          Views::getUserView(Views::SETTINGS_PROFILE),
          $context
        );
        break;
      case 'manage_account':
        $content_body = $this -> renderView(
          Views::getUserView(Views::SETTINGS_MANAGE_ACCOUNT),
          $context
        );
        break;
      case 'privacy':
      case 'teams':
      case 'sync':
      case 'about_us':
        $content_body = "I am " . $user -> getUsername() . " searching for $tab";
        break;
      default:
        $content_body = NULL;
    }

    if(is_null($content_body))
      return self::prepareResponse("Settings tab $tab not found", NULL, 'HTTP/ 1.1 404 Not Found');
    return self::prepareResponse($content_body);
  }

  /**
   * Logs out a user
   */
  public function logout() {
    unset(
      $_SESSION[Request::USER_LOGGED_IN]
    );

    // Go to login
    CustomRedirect::redirect("/login");
  }

  public function test(): Response {
    $db = $this -> db;

    $context = compact('db');
    return self::prepareResponse($this -> renderView(Views::TEST_PAGE, $context));
  }
}
