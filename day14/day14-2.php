<?php

declare(strict_types=1);

const DIRECTIONS = ['N', 'W', 'E', 'S'];

const CYCLES = 1000000000;

$inputFile = $argv[1] ?? 'input-test.txt';
if (!file_exists($inputFile)) {
    die(sprintf('The input file (%s) does not exist.' . PHP_EOL, $inputFile));
}

$map = file($inputFile, FILE_IGNORE_NEW_LINES);
if ($map === false) {
    die(sprintf('Unable to load input file (%s)', $inputFile));
}

// explode string to chars and transpose (col --> row)
$map = array_map(null, array_map('str_split', $map));

$begins = microtime(true);

$slidingBlocks = [];
foreach (DIRECTIONS as $direction) {
    $blocks = [];
    foreach ($map as $row => $cells) {
        $start = 0;
        $length = 0;
        foreach ($cells as $col => $cell) {
            if ($cell !== '#') {
                $length += 1;
            } else {
                if ($length > 1) {
                    $blocks[$row][] = [$start, $length];
                }
                $start = $col + 1;
                $length = 0;
            }
        }
        if ($length > 1) {
            $blocks[$row][] = [$start, $length];
        }
    }
    $slidingBlocks[$direction] = $blocks;
    rotateMapCCW();
}

$states = [[]];
for ($cycle = 1; $cycle <= CYCLES; $cycle++) {
    foreach (DIRECTIONS as $direction) {
        $rowCount = count($map);
        $colCount = count($map[0]);

        $blocks = $slidingBlocks[$direction];
        for ($row = 0; $row < $rowCount; $row++) {
            foreach ($blocks[$row] as [$start, $length]) {
                $block = $length < $colCount
                    ? array_slice($map[$row], $start, $length)
                    : $map[$row];
                $charCounts = array_count_values($block);
                if (count($charCounts) > 1) {
                    $block = [
                        ...array_fill(0, $charCounts['O'], 'O'),
                        ...array_fill($charCounts['O'], $charCounts['.'], '.')
                    ];
                    array_splice($map[$row], $start, $length, $block);
                }
            }
        }

        rotateMapCCW();
    }

    $rowCount = count($map);
    $colCount = count($map[0]);

    $newState = [];
    $blocks = $slidingBlocks['N'];
    for ($row = 0; $row < $rowCount; $row++) {
        foreach ($blocks[$row] as [$start, $length]) {
            $block = $length < $colCount
                ? array_slice($map[$row], $start, $length)
                : $map[$row];
            $charCounts = array_count_values($block);
            $newState[] = $charCounts['O'] ?? 0;
        }
    }

    $sumWeight = 0;
    for ($col = 0; $col < $colCount; $col++) {
        $sumWeight += ($colCount - $col) * count(
            array_filter(
                array_column($map, $col),
                fn(string $char) => $char === 'O'
            )
        );
    }

    $newState[] = $sumWeight;

    foreach ($states as $c => $state) {
        if ($state === $newState) {
            echo 'already existing state in cycle #' . $c . ' of #' . count($states) . PHP_EOL;

            $offset = $c;
            $stateCount = count($states);
            $divisor = $stateCount - $offset;
            $targetState = (CYCLES - $offset) % $divisor + $offset;

            $ends = microtime(true);
            echo array_pop($states[$targetState]) . sprintf(' in %f seconds', $ends - $begins) . PHP_EOL;
            exit;
        }
    }

    $states[] = $newState;
}

function rotateMapCCW(): void
{
    global $map;

    $map = array_map(null, ...array_map('array_reverse', $map));
}
