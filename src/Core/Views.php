<?php

namespace ToDoApp\Core;

readonly class Views {
  // General Views
  public const LOGIN = 'login';
  public const SIGNUP = 'signup';
  public const SIGNUP_2 = 'signup_2';

  // Included views
  public const HEADER = 'header';

  /* -------------------------------------
   * USER VIEWS
   * ------------------------------------- */
  private const USER_VIEWS = 'user/';
  public const USER_DASHBOARD = 'dashboard';
  public const VIEW_TASK = 'view-task';
  public const USER_SETTINGS = 'settings';
  // Settings view     
  public const SETTINGS_PROFILE = 'profile';
  public const SETTINGS_MANAGE_ACCOUNT = 'manage_account';

  /* -------------------------------------
   * ADMIN VIEWS
   * --------------------------------------- */
  private const ADMIN_VIEWS = 'admin/';
  public const ADMIN_DASHBOARD = self::ADMIN_VIEWS . 'dashboard';

  // Utils Views
  public const NOT_FOUND = '404NotFound';
  public const TEST_PAGE = 'test';


  public static function getUserView($user_view): string {
    return self::USER_VIEWS . $user_view;
  }
}
