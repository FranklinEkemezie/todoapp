<?php

namespace ToDoApp\Utils;


class SSEvent {
  private string $id;

  // Server-sent event types
  public const SSEventType_DEFAULT = self::SSEventType_MESSAGE;
  public const SSEventType_MESSAGE = 'message';
  public const SSEventType_UPDATE = 'update';
  public const SSEventType_ALERT = 'alert';
  public const SSEventType_ERROR = 'error';

  /**
   * @param mixed $data Specifies the message to send.
   * @param string $type Specifies the type of the SSE. Must be one of the valid
   * SSEventTypes.
   */
  public function __construct(
    private mixed $data,
    private string $type = self::SSEventType_DEFAULT
  )
  {
    // Generate unique event ID
    $this -> id = explode(".", uniqid('todoapp-sse-', true))[0];
  }

  /**
   * Formats the event for sending
   * 
   * @param array $event An array containing the event information to be formatted.
   * The array must have the keys: 'id', 'type', 'data'
   * 
   * @return string The formatted event
   */
  private function formatEvent(array $event): string {
    return "id: {$event['id']} \ntype: {$event['type']} \ndata: {$event['data']}\n\n";
  }

  /**
   * Sends the Server-Sent Event
   * 
   */
  public function send() {
    // Set headers
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('Connection: keep-alive');

    echo $this; // converts and output the SSEvent object instance as string implicitly using __toString()

    sleep(2);

    flush();

    header('Connection: close');
  }

  /**
   * String representation of the SSEvent
   * 
   */
  public function __toString() {
    $event = array(
      "id" => $this -> id,
      "type" => $this -> type,
      "data" => json_encode($this -> data)
    );

    return self::formatEvent($event);
  }

}