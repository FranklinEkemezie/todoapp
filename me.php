<?php

include __DIR__ . "/src/includes/functions.php";

setDefaults();

$filename = __DIR__ . "/var/log/test.log";

$file = fopen($filename, "a") or die("Unable to open file");

$txt = function() {
  $date = date("D M G:i:s Y");
  
  return "[$date] The problem requires a quick solution" . "\n";
};

for($i = 0 ; $i < 10; $i++) {
  fwrite($file, $txt());
}








?>