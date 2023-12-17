<?php

declare(strict_types=1);

$inputFile = $argv[1] ?? 'input.txt';
if (!file_exists($inputFile)) {
    die(sprintf('The input file (%s) doesn\'t seem to exist.' . PHP_EOL, $inputFile));
}

$input = file($inputFile, FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die(sprintf('Unable to load input file (%s)', $inputFile));
}

// explode string to chars and transpose (col --> row)
$input = array_map(null, ...array_map('str_split', $input));

$maxWeight = count($input[0]);
$sumWeight = 0;
foreach ($input as $row => $chars) {
    $weight = $maxWeight;
    foreach ($chars as $pos => $char) {
        if ($char === 'O') {
            $sumWeight += $weight;
            $weight -= 1;
        } elseif ($char === '#') {
            $weight = $maxWeight - $pos - 1;
        }
    }
}

echo $sumWeight . PHP_EOL;
