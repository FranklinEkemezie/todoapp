<?php

/**
 * Checks if a number is an Armstrong number
 * 
 * @param int $number
 * 
 * @return bool Returns TRUE if the number is Armstrong otherwise FALSE
 */
function isArmStrong(int $number): bool
{
  $is3Digit = strlen(((string) $number)) === 3;
  if (!$is3Digit) {
    echo "$number is not a 3-digit number";
    return false;
  }

  $numberDigits = array_map(function ($str_digit) {
    return (int) $str_digit;
  }, str_split((string) $number));


  $sumOfCubes = array_reduce($numberDigits, function ($prev, $curr) {
    return $prev + ($curr ** 3);
  });

  $isArmStrong = $sumOfCubes === $number;

  if ($isArmStrong) {
    echo "$number is Armstrong";
  } else {
    echo "$number is not Armstrong";
  }

  return $isArmStrong;
}

isArmStrong(153);
