<?php

namespace ToDoApp\Controllers;

use PDO;
use ToDoApp\Core\Request;
use ToDoApp\Core\Response;
use ToDoApp\Exceptions\NotFoundException;
use ToDoApp\Utils\DependencyInjector;
use ToDoApp\Utils\Logger;

class AbstractController {
  private const VIEWS_DIR = __DIR__ . "/../../Views/";
  private const INCLUDE_VIEWS_DIR = __DIR__ . "/../includes/";

  protected PDO $db;
  protected Logger $logger;

  public function __construct(
    DependencyInjector $di,
    protected Request $request
  ) {
    $this -> db = $di -> get('db');
    $this -> logger = $di -> get('logger');

  }

  /**
   * Prepares a response
   * 
   * @param string $status_line The status line
   * @param string $content_type The content type of the response
   * @param string $cache_control Set 'Cache-Control' header
   * @param array $extra_headers An array of extra key-value pairs for the headers
   * @param mixed $content_body The content body of the response
   * 
   * @return Response Returns a Response object
   */
  public static function prepareResponse(
    mixed $content_body,
    string $content_type=NULL,
    string $status_line=NULL,
    string $cache_control=NULL,
    array $extra_headers=[],
  ): Response {
    return new Response(
      $status_line ?: 'HTTP/ 1.1 200 OK',
      array_merge(
        ['Content-Type' => $content_type ?: 'text/html', 'Cache-Control' => $cache_control ?: 'no-cache'],
        $extra_headers
      ),
      $content_body
    );
  }

  /**
   * Renders the view
   * 
   * @param string $view The name of the view to display
   * @param mixed[] $context A map of variable-name => variable-value pair to used in render the view
   * @param bool $include To indicate that the view is to be included from the 'includes' directory
   * 
   * @return string The content of the view
   */
  public function renderView(string $view, array $context = [], bool $include=FALSE): string {
    // Start otuput buffering
    ob_start();

    // Extra data variables for use in the view
    extract($context);

    // Include the view
    $file_dir = !$include ? self::VIEWS_DIR : self::INCLUDE_VIEWS_DIR;
    $filename = $file_dir . $view . ".php";
    if(!file_exists($filename)) {
      throw new NotFoundException("File `$filename` does not exist");
    }
    require $filename;

    // Get the captured output
    $content = ob_get_clean();

    // Return the rendered content
    return $content;
  }
}