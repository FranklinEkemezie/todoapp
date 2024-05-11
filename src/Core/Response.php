<?php

namespace ToDoApp\Core;

class Response {

  public function __construct(
    private string $status_line,
    private array $headers,
    private $response_body
  ) {
  }

  public function getContentType(): string {
    return $this -> headers['Content-Type'];
  }

  public function setHeader($name, $value) {
    $this -> headers[$name] = $value;
  }

  public function getHeader($name) {
    return $this -> headers[$name];
  }

  /**
   * Sends the response as string
   */
  public function __toString() {
    // Send status line
    header($this -> status_line);

    // Send headers
    foreach($this -> headers as $name => $value) {
      header("$name: $value");
    }
  
    return $this -> response_body;
  }
}