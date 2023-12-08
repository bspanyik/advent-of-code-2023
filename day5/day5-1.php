<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

// seeds
$seeds = array_map('intval', explode(' ', str_replace('seeds: ', '', $lines[0])));

$numberOfLines = count($lines);
$currentLine = 3;
$map = [];
while ($currentLine < $numberOfLines) {
    $category = [];
    while ($currentLine < $numberOfLines && $lines[$currentLine] != '') {
        [$destination, $source, $range] = array_map('intval', explode(' ', $lines[$currentLine]));
        $category[] = [$source, $source + $range, $destination - $source];
        $currentLine++;
    }
    usort($category, fn(array $a, array $b) => $a[0] <=> $b[0]);
    $map[] = $category;
    $currentLine += 2;
}

foreach ($seeds as $seed) {
    // echo $seed . ' ';
    foreach ($map as $category) {
        for ($i = 0; $i < count($category); $i++) {
            if ($seed >= $category[$i][0] && $seed < $category[$i][1]) {
                $seed += $category[$i][2];
                break;
            }
        }
        // echo $seed . ' ';
    }
    if (!isset($minSeed)) {
        $minSeed = $seed;
    } else {
        $minSeed = min($seed, $minSeed);
    }
}

echo $minSeed . PHP_EOL;
