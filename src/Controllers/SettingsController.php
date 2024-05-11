<?php

use ToDoApp\Controllers\AbstractController;
use ToDoApp\Core\Request;
use ToDoApp\Core\Response;
use ToDoApp\Core\Views;

class SettingsController extends AbstractController {
  /**
   * Displays the Profile settings
   */
  public function displayProfile(): Response {
    $user = $this -> request -> getSession() -> get(Request::USER_LOGGED_IN);


    $context = compact('user');

    $content_body = $this -> renderView(
      Views::getUserView(Views::SETTINGS_PROFILE),
      $context
    );

    return self::prepareResponse($content_body);
  }

  /**
   * Display the Manage Account settings
   */
  public function displayManageAccount(): Response {
    $user = $this -> request -> getSession() -> get(Request::USER_LOGGED_IN);

    $context = compact('user');

    $content_body = $this -> renderView(
      Views::getUserView(Views::SETTINGS_MANAGE_ACCOUNT),
      $context
    );

    return self::prepareResponse($content_body);
  }
}