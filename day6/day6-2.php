<?php

declare(strict_types=1);

$input = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die('Th
    ere\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$race = [
    'time' => extractNumber($input[0]),
    'distance' => extractNumber($input[1]),
];

/**
 * Brute-forcing it, like in day6-1 takes too much time, so try to be smarter this time:
 * - don't search from 1, try from the ratio of distance/time
 * - only have to find the first winning scenario, then we can calculate all wins
 */
$wins = 0;
for ($time = floor($race['distance'] / $race['time']); $time < round($race['time'] / 2); $time++) {
    $distance = $time * ($race['time'] - $time);
    if ($distance > $race['distance']) {
        $wins = 1 + $race['time'] - 2 * $time;
        break;
    }
}

echo $wins . PHP_EOL;

function extractNumber(string $text): int
{
    return (int) implode(
        array_filter(
            array: explode(' ', $text),
            callback: 'is_numeric'
        )
    );
}
