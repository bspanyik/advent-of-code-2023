<?php

$sum = 0;

$lines = explode(PHP_EOL, trim(file_get_contents('input.txt')));
foreach ($lines as $line) {
    $lineWithNumbersOnly = preg_replace('/[^0-9]/', '', $line);
    $calibrationValue = substr($lineWithNumbersOnly, 0, 1) . substr($lineWithNumbersOnly, -1);
    $sum += (int) $calibrationValue;
}

echo $sum . PHP_EOL;
