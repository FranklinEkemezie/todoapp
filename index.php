<?php
// Declare strict types
declare(strict_types=1);

use ToDoApp\Core\Request;
use ToDoApp\Core\Router;
use ToDoApp\Core\Config;
use ToDoApp\Utils\DependencyInjector;
use ToDoApp\Utils\Logger;

require __DIR__ . "/src/includes/autoload.php";
require __DIR__ . "/src/includes/functions.php";

// Set defaults
setDefaults();

// Start session
session_start();

// Config
$config = Config::getInstance();

// Initialize database
$dbConfig = $config -> get('db');

$host = $dbConfig['hostname'];
$user = $dbConfig['user'];
$password = $dbConfig['password'];
$dbname = $dbConfig['dbname'];
$db = new PDO(
  "mysql:host=$host;dbname=$dbname",
  $user,
  $password
);

// Logger
$default_log_file =  __DIR__ . "//var//log//todoapp.log";
$logger = new Logger('todoapp', $default_log_file);


// Dependency injector: Inject the dependencies to the router
$di = new DependencyInjector;
$di -> set('db', $db);
$di -> set('logger', $logger);

// Initiate Request
$request = new Request;

// Instantiate the Router
$router = new Router($di);

// try {
//   // Route the request
//   $response = $router -> route($request);

//   echo $response;
// } catch(Exception $e) {
//   echo "An error occurred: Cannot handle this request properly at the moment - <b>" .
//   $e -> getMessage() . "</b>";
// }

$response = $router -> route($request);

echo $response;

?>