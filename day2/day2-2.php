<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$sum = 0;
foreach ($lines as $index => $game) {
    $game = str_replace([': ', ', ', '; '], [':', ',', ';'], $game);
    [, $game] = explode(':', $game);
    $sum += getPowerOfMaxNumberOfCubes(explode(';', $game));
}

echo $sum . PHP_EOL;


/** @param string[] $rounds */
function getPowerOfMaxNumberOfCubes(array $rounds): int
{
    /** @var array{'red': int, 'green': int, 'blue': int} $max */
    $max = [
        'red'   => 0,
        'green' => 0,
        'blue'  => 0,
    ];

    foreach ($rounds as $round) {
        $cubes = explode(',', $round);
        foreach ($cubes as $cube) {
            [$number, $color] = explode(' ', $cube);
            $max[$color] = max($number, $max[$color]);
        }
    }

    return $max['red'] * $max['green'] * $max['blue'];
}
