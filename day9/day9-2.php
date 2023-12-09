<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$sum = 0;
foreach ($lines as $line) {
    $values = [];
    $row = 0;
    $values[$row] = array_map('intval', explode(' ', $line));
    do {
        $prevRow = $row;
        $row += 1;
        for ($i = 1; $i < count($values[$prevRow]); $i++) {
            $values[$row][] = $values[$prevRow][$i] - $values[$prevRow][$i - 1];
        }
        $diffNum = count(array_unique($values[$row]));
    } while ($diffNum > 1);

    $prev = 0;
    for ($i = $row; $i >= 0; $i--) {
        $value = $values[$i][0];
        $prev = $value - $prev;
    }
    $sum += $prev;
}

echo $sum . PHP_EOL;
