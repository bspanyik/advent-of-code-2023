<?php

declare(strict_types=1);

const MAX_CUBES_OF_COLOR = ['red' => 12, 'green' => 13, 'blue' => 14];

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$sum = 0;
foreach ($lines as $index => $game) {
    $game = str_replace([': ', ', ', '; '], [':', ',', ';'], $game);
    [, $game] = explode(':', $game);
    if (isValidGame(explode(';', $game))) {
        $sum += $index + 1;
    }
}

echo $sum . PHP_EOL;

/** @param string[] $rounds */
function isValidGame(array $rounds): bool
{
    foreach ($rounds as $round) {
        $cubes = explode(',', $round);
        foreach ($cubes as $cube) {
            [$number, $color] = explode(' ', $cube);
            if ($number > MAX_CUBES_OF_COLOR[$color]) {
                return false;
            }
        }
    }

    return true;
}
