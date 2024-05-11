<?php

// Register class autoloade
spl_autoload_register(function ($classname) {
  $classname_arr = explode("\\", $classname);
  $classname_arr[0] = "src";

  $class_base = implode("\\", $classname_arr);
  $filename = __DIR__ . "\..\..\\" . $class_base . ".php";


  if(file_exists($filename)) {
    require_once $filename;
  } else {
    throw new Error("Failed opening class $classname in $filename. File not found!");
  }
});

