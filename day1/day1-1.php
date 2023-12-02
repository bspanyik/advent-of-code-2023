<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$sum = 0;
foreach ($lines as $line) {
    $lineWithNumbersOnly = (string) preg_replace('/[^0-9]/', '', $line);
    $calibrationValue = substr($lineWithNumbersOnly, 0, 1) . substr($lineWithNumbersOnly, -1);
    $sum += (int) $calibrationValue;
}

echo $sum . PHP_EOL;
