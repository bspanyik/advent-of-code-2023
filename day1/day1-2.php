<?php

declare(strict_types=1);

const DIGITS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$sum = 0;
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

    return reset($digits) * 10 + end($digits);
}
