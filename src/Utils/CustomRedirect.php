<?php

namespace ToDoApp\Utils;


/**
 * CustomRedirect class - Handle URL redirection
 */

class CustomRedirect {
  /**
   * Redirect a page to the give URL
   * 
   * @param string $url The URL to redirect to
   * @param string $time_delay Number of seconds to delay before redirection
   */
  public static function redirect(string $url, int $time_delay=NULL, string $redirect_info=NULL) {
    if(is_null($time_delay)) {
      header("Location: $url");
    } else {
      header("refresh: $time_delay; url=$url");
    }
    exit;
  }
}