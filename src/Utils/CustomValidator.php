<?php

namespace ToDoApp\Utils;

use Exception;

/**
 * Custom Validator for user/form inputs
 * 
 * @author Ekemezie Franklin
 * 
 */
class CustomValidator {
  private bool $isValid;
  private string $errorMsg;

  public const VALIDATE_AS_USERNAME = 49;
  public const VALIDATE_AS_EMAIL = 59;
  public const VALIDATE_AS_PASSWORD = 69;
  public const VALIDATE_NOT_EMPTY = 79;

  /**
   * @param $input The input to be validated
   * @param int $format A valid CustomValidator validation format. Optional - 
   * If specified, the constructor function automatically validates using the validation format
   * 
   * @return CustomValidator Returns a CustomValidator object with properties accessible using 
   * isValid() or getErrorMsg()
   * 
   */
  public function __construct(private $input, int $format=NULL) {
    if(!is_null($format)) {
      $this -> validate($format);
    }
  }

  /**
   * 
   */
  private static function validatorOutput(
    bool $isValid=TRUE,
    string $errorMsg=""
  ): array {
    return array(
      "isValid" => $isValid,
      "errorMsg" => $errorMsg
    );
  }

  private static function sanitizeText(string $input): string {
    return htmlspecialchars(
        stripslashes(
          trim($input)
      )
    );
  }

  /**
   * Sanitizes the input
   */
  public function sanitizeInput(): mixed {
    if(is_string($this -> input)) {
      return self::sanitizeText($this -> input);
    }
    return $this -> input;
  }

  /**
   * Specifies whether the input is valid.
   * The input must be validated first using the CustomValidator::validate() method
   * 
   * @return bool Returns TRUE if the input is valid and FALSE otherwise.
   */
  public function isValid(): bool {
    return $this -> isValid;
  }

  /**
   * Specifies the error message.
   * The input must be validate first using the CustomValidator::validate() method
   * 
   * @return string Returns the error message for the validated input
   */
  public function getErrorMsg(): string {
    return $this -> errorMsg;
  }

  public function validate(int $format) {
    $res = NULL;
    switch($format) {
      case self::VALIDATE_AS_EMAIL:
        $res = self::validateEmail($this -> input);
        break;
      case self::VALIDATE_AS_USERNAME:
        $res = self::validateUsername($this -> input);
        break;
      case self::VALIDATE_AS_PASSWORD:
        $res = self::validatePassword($this -> input);
        break;
      case self::VALIDATE_NOT_EMPTY:
        $isValid = !empty($this -> input) && preg_match("/.+/", $this -> input);
        $errorMsg = !$isValid ? 'This field is required' : '';

        $res = array('isValid' => $isValid, 'errorMsg' => $errorMsg);
        break;
      default:
        throw new Exception("Unknown validator format $format");
    }

    $this -> isValid = $res['isValid'];
    $this -> errorMsg = $res['errorMsg'];

    return $this;
  }


  /**
   * Validates an email address given as input
   * @param string $email The email address to validate
   * 
   * @return array Returns an array with specifying whether the input is valid
   * and the corresponding error message
   */
  private static function validateEmail(string $email): array {
    $email = self::sanitizeText($email);

    $isEmpty = empty($email) || strlen($email) <= 0;
    if($isEmpty) {
      return self::validatorOutput(false, "Email cannot be empty");
    }

    $emailRegex = "/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}/";
    $isValid = preg_match($emailRegex, $email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    if(!$isValid) {
      return self::validatorOutput(false, "Email address is not valid");
    }
    return self::validatorOutput();
  }

  /**
   * @param string $username The username input to validate
   * 
   * @return array Returns an array with specifying whether the input is valid
   * and the corresponding error message
   */
  private static function validateUsername(string $username): array {
    $username = self::sanitizeText($username);

    $isEmpty = empty($username) || strlen($username) <= 0;
    if($isEmpty) {
      return self::validatorOutput(false, "Username cannot be empty");
    }

    if(strlen($username) <= 3) {
      return self::validatorOutput(false, "Username is too short");
    }

    if(strlen($username) >= 16) {
      return self::validatorOutput(false, "Username is too long");
    }

    $hasWhitespace = preg_match("/[\s\p{Z}]/u", $username);
    if($hasWhitespace) {
      return self::validatorOutput(false, "Whitespaces are not allowed");
    }

    $isValid = preg_match("/^[a-zA-Z0-9_-]{3,16}$/", $username);
    if(!$isValid) {
      return self::validatorOutput(false, "Username is invalid");
    }

    return self::validatorOutput();
  }


  private static function validatePassword(string $password): array {
    $password = self::sanitizeText($password);

    $isEmpty = empty($password) || strlen($password) <= 0;
    if($isEmpty) {
      return self::validatorOutput(false, "Password cannot be empty");
    }

    if(strlen($password) < 8) {
      return self::validatorOutput(false, "Password is too short");
    }

    if(!preg_match("/[A-Z]/", $password)) {
      return self::validatorOutput(false, "Password $password must contain at least one uppercase letter");
    }

    if(!preg_match("/[a-z]/", $password)) {
      return self::validatorOutput(false, "Password must contain at least one lowercase");
    }

    if(!preg_match("/[^a-zA-Z0-9]/", $password)) {
      return self::validatorOutput(false, "Password must have at least one special character");
    }

    return self::validatorOutput();
  }

  /**
   * Validates the input using the given regular expression pattern
   * 
   * @param $regex The regular expression pattern to use for validation
   * 
   * @return bool Returns TRUE if the regex matches, otherwise FALSE.
   */
  public function validateRegex($regex): bool {
    return preg_match($regex, $this -> input);
  }
}