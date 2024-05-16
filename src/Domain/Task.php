<?php

namespace ToDoApp\Domain;


class Task implements \JsonSerializable {

  // Status
  private const STATUS = array(
    1 => "To Do",
    2 => "In Progress",
    3 => "Completed"
  );

  // Priority
  private const PRIORITY = array(
    5 => "Urgent - Important",
    4 => "Urgent - Not Important",
    3 => "Not Urgent - Important",
    2 => "Not Urgent - Not Important",
    1 => "Leisure"
  );

  public function __construct(
    private string $task_id,
    private string $title,
    private string $description,
    private int $status,
    private int $priority,
    private string $category,
    private string $date_created,
    private string $date_due
  )
  {
    
  }

  public function jsonSerialize() {
    return array(
      'task_id' => $this -> task_id,
      'title' => $this -> title,
      'description' => $this -> description,
      'status' => $this -> status,
      'priority' => $this -> priority,
      'date_created' => $this -> date_created,
      'date_due' => $this -> date_due
    );
  }

  public function getTaskId(): string {
    return $this -> task_id;
  }

  public function getTitle(): string {
    return $this -> title;
  }

  public function getDescription(): string {
    return $this -> description;
  }

  public function getPriority($priority_code=FALSE): int|string {
    if($priority_code) return (int) $this -> priority;

    return self::PRIORITY[$this -> status];
  }

  public function getStatus($status_code=FALSE): int|string {
    if($status_code) return (int) $this -> status;

    return self::STATUS[$this -> status];
  }

  public function getCategory(): string {
    return $this -> category;
  }

  public function getDateCreated($format="Y-m-d"): string|null {
    if(is_null($this -> date_due) || empty($this -> date_due)) return NULL;

    return date_format(
      date_create($this -> date_created),
      $format
    );
  }

  public function getDateDue($format="Y-m-d"): string|null {
    if(is_null($this -> date_due) || empty($this -> date_due)) return NULL;

    return date_format(
      date_create($this -> date_due),
      $format
    );
  }

}