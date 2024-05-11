<?php

namespace ToDoApp\Core;

use ErrorException;
use ToDoApp\Controllers\ErrorController;
use ToDoApp\Utils\CustomRedirect;
use ToDoApp\Utils\DependencyInjector;

class Router {
  private array $route_map;

  const ROUTE_MAP_FILE = __DIR__ . "/../../config/routes.json";
  const CONTROLLER_NAMESPACE = "\\ToDoApp\\Controllers";
  const REGEX_PATTERNS = array(
    "number" => "\d+",
    "string" => "\w+",
  );


  public function __construct(private DependencyInjector $di) {
    // Fetch the routes.json file
    $this -> route_map = json_decode(
      file_get_contents(self::ROUTE_MAP_FILE),
      TRUE
    ) ?? throw new ErrorException("Something went wrong!");
    
  }

  /**
   * Gets the corresponding regex path to match a path to the route using the route info
   * 
   * @param string $route The route to be used to generate the regex
   * @param array $info An associative array of information about the route. This information is
   * used to generate the regex pattern for the route
   * 
   * @return string The regex pattern for the route 
   */
  private static function getRouteRegex(string $route, $info): string {
    // Split the route path
    $route_path_ = explode("/", $route);
    $route_regex_ = array();

    foreach($route_path_ as $path) {
      // Identify parameterized path
      if($path[0] === ":") {
        $param = substr($path, 1);
        $data_type = $info["params"][$param];
        $data_type_regex = self::REGEX_PATTERNS[$data_type];
        
        // Replace the parameterized path with the corresponding regular expression
        $path = $data_type_regex; 
      }

      $route_regex_[] = $path;
    }

    $route_regex = implode("\/", $route_regex_);
    
    return "/^$route_regex$/";
  }

  /**
   * Modifies the route path to make it friendly with the default route
   * 
   * @param string $path_route The path route to modify
   * @param string $route The default route for the path
   * 
   * @return array Returns an array [$modified, $path_route] containing a boolean $modified specifying
   * whether $path_route was modified and $path_route which is modifed route path
   */
  private static function modifyRoutePath(string $path_route, string $route): array {
    $modified = false; // Tracks if the path is modified

    // Remove multiple slashes - more than two slashes
    if(preg_match("/\/{2,}/", $path_route)) {
      $path_route = preg_replace("/\/{2,}/", "/", $path_route);
      $modified = true;
    }

    // Remove first slash
    if($path_route[0] === "/") {
      $path_route = substr($path_route, 1);
      $modified = true;
    }

    // Remove last/terminal slash
    if(strrev($path_route)[0] === "/") {
      $path_route = substr($path_route, 0, strlen($path_route) - 1);
      $modified = true;
    }

    // Modify to lowercase where necessary
    $path_route_ = explode("/", $path_route);
    $route_ = explode("/", $route);

    if(count($path_route_) === count($route_)) {
      for($i = 0; $i < count($path_route_); $i++) {
        if(!preg_match("/^:/", $route_[$i])) {
          if(
            // path route is the same but with different character case for each path
            ($path_route_[$i] !== $route_[$i]) &&
            (strtolower($path_route_[$i]) === strtolower($route_[$i]))
          ) {
            $path_route_[$i] = strtolower($path_route_[$i]);
            $modified = true;
          }
        }
      }
    }

    $path_route = implode("/", $path_route_);

    return [
      $modified,
      $path_route
    ];
  }

  /**
   * Extract the parameters from the request path
   * 
   * @param string $route The route for matching and extracting the parameters
   * @param string $path The request to extract the parameters from
   * 
   * @return array An associative array of the extracted parameters with the key as the variable name of the parameter
   * and the corresponding value as the value of the parameter.
   */
  private static function extractParams(string $route, string $path): array {
    $params = [];

    $route_parts = explode("/", $route);
    $path_parts = explode("/", $path);

    foreach($route_parts as $index => $route_part) {
      if($route_part[0] === ":") {
        $params[substr($route_part, 1)] = $path_parts[$index];
      }
    }

    return $params;
  }


  /**
   * Routes the request to the appropriate controller to handle the request
   * 
   * @param Request $request The request to route
   */
  public function route(Request $request): Response {
    // Route the request to the appropriate controller
    $path_route = substr($request -> getPath(), 1);

    foreach($this -> route_map as $route => $info) {
      $modify_route_path = self::modifyRoutePath($path_route, $route);
      $path_is_modified = $modify_route_path[0];
      $modified_path = $modify_route_path[1];

      // Modify route paths
      if($path_is_modified) {
        CustomRedirect::redirect("/" . $modified_path);
      }

      if(preg_match(self::getRouteRegex($route, $info), $path_route)) {
        // Check if authentication is required
        $authentication_required = $info['authentication'] ?? false;
        if($authentication_required && !$request -> isAuthenticated())  {
          CustomRedirect::redirect("/login");

          $errorController = new ErrorController($this -> di, $request);
          return $errorController -> notAuthenticated();
        }
        
        return $this -> executeController($route, $request);
      }
    }

    $errorController = new ErrorController($this -> di, $request);
    return $errorController -> notFound();
  }

  /**
   * Executes the controller
   * 
   * @param string $route The route
   * @param Request $request The request
   * 
   * @return Response The response
   */
  private function executeController($route, $request): Response {
    $controller_name = $this -> route_map[$route]["controller"] . "Controller";
    $controller_name = self::CONTROLLER_NAMESPACE . "\\" . $controller_name; 
    $controller = new $controller_name($this -> di, $request);
    $controller_method = $this -> route_map[$route]["method"];

    $path = substr($request -> getPath(), 1);
    $params = self::extractParams($route, $path);

    return call_user_func(
      [$controller, $controller_method],
      ...$params
    );
  }
}