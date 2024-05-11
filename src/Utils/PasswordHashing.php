<?php

namespace ToDoApp\Utils;

class PasswordHashing {

  /**
   * Hashes a password
   * 
   * @param string $password The password to hash
   * 
   * @return string Returns the hashed password 
   */
  public static function hashPassword(string $password): string {
    return password_hash(
      $password,
      PASSWORD_BCRYPT
    );
  }

  /**
   * Verifies if the password and the hashed password match
   * 
   * @param string $password The raw password string
   * @param string $hashed_password The hashed password
   * 
   * @return bool Returns TRUE if the password matches the hashed password, otherwise FALSE
   * 
   */
  public static function verifyPassword(string $password, string $hashed_password): bool {
    return password_verify(
      $password,
      $hashed_password
    );
  }


}