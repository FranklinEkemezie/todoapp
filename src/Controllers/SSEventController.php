<?php

namespace ToDoApp\Controllers;

use ToDoApp\Utils\SSEvent;

class SSEventController extends AbstractController {
  public function handleSSEvent() {
    if($_SERVER['HTTP_ACCEPT'] === "text/event-stream") {
      $sse = new SSEvent("Hello World!", SSEvent::SSEventType_ERROR);

      if(
        !empty($this -> request -> getParams() -> getString('data')) &&
        !empty($this -> request -> getParams() -> getString('type'))
      ) {
        $sse -> send();
      }
    } else {
      http_response_code(404);
      exit;
    }

  }
}