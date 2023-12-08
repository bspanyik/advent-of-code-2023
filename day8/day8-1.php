<?php

$input = file_get_contents($argv[1] ?? 'input.txt');
if ($input === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$lines = explode("\n\n", $input);

$directions = str_split($lines[0]);
$map = [];
foreach (explode("\n", $lines[1]) as $row) {
    $map[substr($row, 0, 3)] = [
        'L' => substr($row, 7, 3),
        'R' => substr($row, 12, 3),
    ];
}

$countDirections = count($directions);
$steps = 0;
$key = 'AAA';
do {
    $direction = $directions[$steps % $countDirections];
    $key = $map[$key][$direction];
    $steps += 1;
} while ($key !== 'ZZZ');

echo $steps . PHP_EOL;
