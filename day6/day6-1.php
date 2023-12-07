<?php

declare(strict_types=1);

$input = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$races = array_map(
    fn(int $time, int $distance) => ['time' => $time, 'distance' => $distance],
    extractNumbers($input[0]),
    extractNumbers($input[1]),
);

$totalWins = 1;
foreach ($races as $race) {
    $wins = 0;
    for ($push = 1; $push < $race['time'] - 1; $push++) {
        $distance = $push * ($race['time'] - $push);
        if ($distance > $race['distance']) {
            $wins += 1;
        }
    }
    $totalWins *= $wins;
}

echo $totalWins . PHP_EOL;

/** @return int[] */
function extractNumbers(string $text): array
{
    return array_map(
        callback: 'intval',
        array: array_filter(
            array: explode(' ', $text),
            callback: 'is_numeric'
        )
    );
}
