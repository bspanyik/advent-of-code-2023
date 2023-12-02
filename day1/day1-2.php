<?php

const DIGITS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

$sum = 0;

$lines = explode(PHP_EOL, trim(file_get_contents('input.txt')));
foreach ($lines as $line) {
    $sum += getCalibrationValue($line);
}

echo $sum . PHP_EOL;


function getCalibrationValue(string $line): int
{
    $digits = [];

    foreach (DIGITS as $index => $digit) {
        $pos = strpos($line, $digit);
        if ($pos !== false) {
            $digits[$pos] = $index < 10 ? $index : $index - 9;
        }

        $pos = strrpos($line, $digit);
        if ($pos !== false) {
            $digits[$pos] = $index < 10 ? $index : $index - 9;
        }
    }
        
    if (empty($digits)) {
        return 0;
    }
        
    ksort($digits);
    $calibrationValue = reset($digits) * 10 + end($digits);

    return (int) $calibrationValue;
}