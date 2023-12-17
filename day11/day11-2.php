<?php

declare(strict_types=1);

// -1 because of the empty rows and columns that are already there ;)
const EXPANSION_FACTOR = 1000000 - 1;

$inputFile = $argv[1] ?? 'input.txt';
if (!file_exists($inputFile)) {
    die(sprintf('The input file (%s) does not exist.' . PHP_EOL, $inputFile));
}

$universe = file($inputFile, FILE_IGNORE_NEW_LINES);
if ($universe === false) {
    die(sprintf('Unable to load input file (%s)', $inputFile));
}

$universeWidth = strlen($universe[0]);

// collect galaxies with calculated vertical expansion
$galaxies = [];
$expansion = 0;
foreach ($universe as $row => $universeLine) {
    if (!str_contains($universeLine, '#')) {
        $expansion += 1;
    } else {
        for ($col = 0; $col < $universeWidth; $col++) {
            if ($universeLine[$col] === '#') {
                $galaxies[] = [$row + $expansion * EXPANSION_FACTOR, $col];
            }
        }
    }
}

// empty columns = where no galaxy were
$emptyCols = array_diff(
    range(0, $universeWidth - 1),
    array_unique(
        array_column($galaxies, 1)
    )
);

// start at the biggest one
rsort($emptyCols);

// for every empty column, galaxies have to be pushed horizontally
$galaxyIndex = array_keys($galaxies);
foreach ($emptyCols as $emptyCol) {
    $pushIndex = array_filter($galaxyIndex, fn(int $index) => $galaxies[$index][1] > $emptyCol);
    foreach ($pushIndex as $index) {
        $galaxies[$index][1] += EXPANSION_FACTOR;
    }
}

$sum = 0;
$galaxyCount = count($galaxies);
for ($i = 0; $i < $galaxyCount - 1; $i++) {
    for ($j = $i; $j < $galaxyCount; $j++) {
        $sum += abs($galaxies[$i][0] - $galaxies[$j][0]) + abs($galaxies[$i][1] - $galaxies[$j][1]);
    }
}

echo $sum . PHP_EOL;
