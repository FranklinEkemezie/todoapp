<?php

namespace ToDoApp\Core;

use ToDoApp\Utils\FilteredMap;

class Request {
  private string $host;
  private int $port;
  private string $method;
  private string $path;
  private bool $https;
  private FilteredMap $params;
  private FilteredMap $session;
  private bool $is_authenticated;

  // Request method constant
  private const GET = 'GET';
  private const POST = 'POST';

  // Request authentication constant
  public const USER_LOGGED_IN = 'U4251';
  public const USER_SIGNUP_TRACKED = 'U6134';

  // Request constants
  public const PREV_REQUEST = 'prev_request';


  public function __construct() {
    $this -> host = $_SERVER['SERVER_NAME'];
    $this -> port = (int) $_SERVER['SERVER_PORT'];
    $this -> method = strtoupper($_SERVER['REQUEST_METHOD']);
    $this -> path = ($_SERVER['REQUEST_URI']);
    $this -> https = isset($_SERVER['HTTPS']);

    $this -> path = parse_url(
      $this -> getUrl(),
      PHP_URL_PATH
    );

    $this -> params = new FilteredMap(
      array_merge($_POST, $_GET)
    );
    $this -> session = new FilteredMap($_SESSION);
    $this -> is_authenticated = null !== $this -> session -> get(self::USER_LOGGED_IN) &&
      !empty($this -> session -> get(self::USER_LOGGED_IN));

    
    // If the request is not by API, i.e. from browser
    // Save the last visited site
    if(!$this -> isAPIRequest())
      $_SESSION[self::PREV_REQUEST] = $this;
  }


  /**
   * The name of the host server
   * 
   * @return string Returns the hostname of the server
   */
  public function getHost(): string {
    return $this -> host;
  }

  /** 
   * The port used on the user's machine to communicate wiith the web server
   * 
   * @return int Returns the port being used on the user's machine
   * to communicate with the web server
   */
  public function getPort(): int {
    return $this -> port;
  }

  /**
   * The path of the request
   * 
   * @return string Returns the path of  the request
   */
  public function getPath(): string {
    return $this -> path;
  }

  /**
   * The method for the request
   * 
   * @return string Returns the request method
   */
  public function getMethod(): string {
    return $this -> method;
  }

  /**
   * Whether the request is by HTTPs
   * 
   * @return bool Whether the request is by HTTPs
   */
  public function isHTTPS(): bool {
    return $this -> https;
  }

  /**
   * Whether the request is GET
   * 
   * @return bool Whether the request is GET
   */
  public function isGet(): bool {
    return $this -> method === self::GET;
  }

  /**
   * Whether the request is POST
   * 
   * @return bool Whether the request is POST
   */
  public function isPost(): bool {
    return $this -> method === self::POST;
  }

  /**
   * Whether the request is authenticated
   * 
   * @return bool Returns TRUE if the user is logged in, otherwise FALSE
   */
  public function isAuthenticated(): bool {
    return $this -> is_authenticated;
  }

  /**
   * Gets the protocol for the request
   * 
   * @return string Returns the protocol for the request
   */
  public function getProtocol(): string {
    return $this -> https ? "https://" : "http://";
  }

  /**
   * Gets the request URL
   * 
   * @return string Returns the request URL
   */
  public function getUrl(): string {
    return
      $this -> getProtocol() .
      $this -> getHost() . ":" .
      $this -> getPort() .
      $this -> getPath();
  }

  /**
   * Gets the GET parameters of the request
   * 
   * @return FilteredMap Returns the GET and POST parameters of the request URL as a FilteredMap
   */
  public function getParams(): FilteredMap {
    return $this -> params;
  }

  /**
   * Gets the session variables
   * 
   * @return FilteredMap Returns the session variables
   */
  public function getSession(): FilteredMap {
    return $this -> session;
  }

  /**
   * Gets the query parameters of the request URL
   * 
   * @param FilteredMap Returns the query parameters of the request URL as a FilteredMap object
   */
  public function getQueryParameters(): FilteredMap {
    $query_params = parse_url($this -> getUrl(), PHP_URL_QUERY);
    parse_str($query_params, $query_params);

    return new FilteredMap($query_params);
  }

  /**
   * Checks if the request is an API request or browser request
   * 
   * @return bool Returns TRUE if the request is an API request, and FALSE if otherwise.
   */
  public function isAPIRequest(): bool {
    if($_SERVER['HTTP_USER_AGENT']) {
      $user_agent = $_SERVER['HTTP_USER_AGENT'];

      if(strpos($user_agent, 'Mozilla') !== false) {
        return false;
      } else {
        return true;
      }
    }

    return false;
  }

}


?>