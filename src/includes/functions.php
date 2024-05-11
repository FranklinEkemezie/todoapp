<?php

function setDefaults() {
  // Set default timezone
  date_default_timezone_set("Africa/Lagos");

}

/**
 * Inserts a value
 * 
 * @param mixed $value The value to insert
 * @param bool $required Specifies whether the value must be defined or have value
 * 
 * Display a value on the screen if the value is set or has a value.
 * When $require is set to TRUE, the value is expected to have a value.
 * When $require is set to FALSE, the value is ignored when it is not set or undefined.
 */
function insert($value=NULL, bool $required=FALSE) {
  $isset = isset($value);

  if($required && $isset) {
    echo htmlspecialchars($value);
  } elseif ($required && !$isset) {
    throw new Exception("Undefined variable ");
  } elseif (!$required && $isset) {
    echo htmlspecialchars($value);
  } elseif(!$required && !$isset) {
    echo "";
  }
}


/**
 * Insert a value from a callback function
 * 
 * @param callable $callable The callable that returns the value to be inserted
 * @param array $arguments The arguments to pass to the callback function
 * @param string $alt An alternate text to display if callback value is empty
 */
function insertc(callable $callable, array $args=[], string $alt="") {
  $val = $callable(...$args);
  insert(!empty($val) ? $val : $alt);
}

/**
 * Insert a date
 * 
 * @param string $date The date to be inserted
 * @param string $format The format of the date to be inserted
 * @param string $alt An alternate text to display if the date string is empty
 */
function insertdate(string $date, string $format="Y-m-d", string $alt="") {
  $date_formatted = !empty($date) ? date_format(date_create($date), $format) : $alt;
  insert($date_formatted);
}

/**
 * Greets the user
 * 
 * @return string Returns a greeting based on the time of the day
 */
function greet(): string {
  $hour = (int) date("H", time());

  if($hour <= 12) {
    return "Good Morning";
  } elseif($hour > 12 && $hour <= 15) {
    return "Good Afternoon";
  } elseif($hour > 15) {
    return "Good Evening";
  }

  return "Welcome";
}

/**
 * Include a script/file with context variables
 * 
 * @param string $filename The filename of the file/script to include
 * @param mixed[] $context A map of variable-name => variable-value pair to used in render the view
 * @param bool $required Specify if the script or file to be included is required
 * 
 */
function includeScript(string $filename, array $context=[], bool $required=TRUE) {
  extract($context);

  if(!$required) include $filename;

  require $filename;
}

/**
 * Include the common page header
 * 
 * @param string $page_title The title of the page
 * @param mixed[] $context A map of variable-name => variable-value pair to used in render the view
 */
function includePageHeader(string $page_title, array $context=[]) {
  $context["title"] = $page_title;

  includeScript(__DIR__ . "/header.php", $context);
}

/**
 * Mark an input as selected when the condition is TRUE
 * 
 * @param bool $condition The condition to be satisfied
 */
function mark_as_selected_if(bool $condition) {
  echo $condition ? "selected" : "";
}

/**
 * Mark an input as selected if the value and the value to be selected is equal. Outputs the value
 * 
 * @param mixed $value To value of the input
 * @param mixed $select_value The value of the input to be selected
 */
function mark_as_selected_if_value(mixed $value, mixed $select_value) {
  echo "value='$value'"; // output the value
  mark_as_selected_if($value === $select_value);
}

/**
 * Compresses an image
 * 
 * @param string $image_path The path to the image to compress
 * @param string $output_path The path to save the compressed image file to
 * @param string $img_type The image file format/type. Supported formats are: 'gif', 'png', 'jpg', 'jpeg',
 * 'avif', 'webp'
 * @param string $img_output_type The format/file type of the outpur image after compressing.
 * Default is the orginal type of the image
 * 
 * @return bool Returns TRUE on success or FALSE on failure
 */
function compress_image(string $image_path, string $output_path, string $img_type, string $img_output_type=NULL): bool {
  try {
    switch($img_type) {
      case "png":
        $image = imagecreatefrompng($image_path);
        break;
      case "jpeg":
      case "jpg":
        $image = imagecreatefromjpeg($image_path);
        break;
      case "gif":
        $image = imagecreatefromgif($image_path);
        break;
      case "avif":
        $image = imagecreatefromavif($image_path);
        break;
      case "webp":
        $image = imagecreatefromwebp($image_path);
        break;
      default:
        throw new Exception("Image format not supported");
    }
  } catch(Exception $e) {
    throw new Exception("Couldn't compress image. Something went wrong: " . $e -> getMessage());
  }

  if(!($image instanceof GdImage)) {    
    throw new Exception("Iamge format not supported: Image is not a vaild " . strtoupper($img_type));
  }

  $width = imagesx($image);
  $height = imagesy($image);

  $new_width = 100;
  $new_height = ($height / $width) * $new_width;

  $image_resized = imagecreatetruecolor($new_width, $new_height);

  imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

  if(is_null($img_output_type)) $img_output_type = $img_type;
  switch($img_output_type) {
    case "png":
      $success = imagepng($image_resized, $output_path, 10);
      break;
    case "jpg":
    case "jpeg":
      $success = imagejpeg($image_resized, $output_path, 10);
      break;
    case "gif":
      $success = imagegif($image_resized, $output_path);
      break;
    case "avif":
      $success = imageavif($image_resized, $output_path, 10);
      break;
    case "webp":
      $success = imagewebp($image_resized, $output_path, 10);
      break;
    default:
      throw new ErrorException("Output image file format not supported");
  }

  imagedestroy($image);
  imagedestroy($image_resized);

  return $success;
}
