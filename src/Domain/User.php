<?php

namespace ToDoApp\Domain;

class User {
  
  public function __construct(
    private string $username,
    private string $email,
    private string $password,
    private ?string $firstname=NULL,
    private ?string $lastname=NULL
  )
  {
    
  }

  public function getUsername(): string {
    return $this -> username;
  }

  public function getEmail(): string {
    return $this -> email;
  }

  public function getPassword(): string {
    return $this -> password;
  }

  public function getFirstname(): string {
    return $this -> firstname ?: "";
  }

  public function getLastname(): string {
    return $this -> lastname ?: "";
  }

  public function getFullname(): string {
    return 
      $this -> getFirstname() .
      $this -> getLastname();
  }
}