<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$sum = 0;
foreach ($lines as $line) {
    [, $card] = explode(': ', $line);
    $numbers = array_map(
        fn ($item) => array_filter(
            explode(' ', $item)
        ),
        explode(' | ', $card)
    );
    $matches = count(array_intersect($numbers[1], $numbers[0]));
    if ($matches > 0) {
        $sum += pow(2, $matches - 1);
    }
}

echo $sum . PHP_EOL;
