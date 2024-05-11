<?php

namespace ToDoApp\Controllers;

use ToDoApp\Core\Response;
use ToDoApp\Core\Views;

class ErrorController extends AbstractController {
  public function notFound(): Response {
    $request_url = $this -> request -> getUrl();

    $context = compact('request_url');
    $content_body = $this -> renderView(Views::NOT_FOUND, $context);

    return self::prepareResponse($content_body, "text/html", "HTTP/ 1.1 404 Not Found");
  }

  public function notAuthenticated(): Response {
    $request_url = $this -> request -> getUrl();

    return self::prepareResponse("Request: $request_url not authenticated", NULL, 'HTTP/ 1.1 401 Unauthorized');
  }
}