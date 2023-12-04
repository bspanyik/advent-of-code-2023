<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$copies = array_fill(1, count($lines), 1);

foreach ($lines as $index => $line) {
    $cardNumber = $index + 1;
    [, $card] = explode(': ', $line);
    $numbers = array_map(
        fn ($item) => array_filter(
            explode(' ', $item)
        ),
        explode(' | ', $card)
    );
    $matches = count(array_intersect($numbers[1], $numbers[0]));
    if ($matches > 0) {
        for ($i = 0; $i < $copies[$index + 1 ]; $i++) {
            for ($j = 1; $j <= $matches; $j++) {
                $copies[$index + 1 + $j] += 1;
            }
        }
    }
}

echo array_sum($copies) . PHP_EOL;
