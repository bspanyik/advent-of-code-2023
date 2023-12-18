<?php

declare(strict_types=1);

const DIRECTIONS = [1 => [0, 1], [1, 0], [0, -1], [-1, 0]];

const MAX_STEPS_IN_A_DIRECTION = 10;
const MIN_STEPS_BEFORE_TURN_OR_STOP = 4;

$inputFile = $argv[1] ?? 'input.txt';
if (!file_exists($inputFile)) {
    die(sprintf('The input file (%s) does not exist.' . PHP_EOL, $inputFile));
}

$map = file($inputFile, FILE_IGNORE_NEW_LINES);
if ($map === false) {
    die(sprintf('Unable to load input file (%s)' . PHP_EOL, $inputFile));
}

$map = array_map('str_split', $map);
$mapHeight = count($map);
$mapWidth = count($map[0]);

$begins = microtime(true);

$seen = [];
$priorityQueue = new SplMinHeap();

$heatLoss = 0;
$row = 0;
$col = 0;
$dRow = 0;
$dCol = 0;
$steps = MAX_STEPS_IN_A_DIRECTION;   // since we don't have a previous direction, we have to find a new one

$priorityQueue->insert([$heatLoss, $row, $col, $dRow, $dCol, $steps]);

while (!$priorityQueue->isEmpty()) {
    /** @var array<int, int> $q */
    $q = $priorityQueue->extract();
    [$heatLoss, $row, $col, $dRow, $dCol, $steps] = $q;

    if ($row === $mapHeight - 1 && $col === $mapWidth - 1 && $steps >= MIN_STEPS_BEFORE_TURN_OR_STOP) {
        $ends = microtime(true);
        echo sprintf('%d in %f seconds.' . PHP_EOL, $heatLoss, ($ends - $begins));
        exit;
    }

    // emulating a Set with array-keys
    if (isset($seen[$row][$col][$dRow][$dCol][$steps])) {
        continue;
    }

    $seen[$row][$col][$dRow][$dCol][$steps] = 1;

    if ($steps < MAX_STEPS_IN_A_DIRECTION) {
        $nextRow = $row + $dRow;
        $nextCol = $col + $dCol;
        if ($nextRow >= 0 && $nextRow < $mapHeight && $nextCol >= 0 && $nextCol < $mapWidth) {
            $priorityQueue->insert([
                $heatLoss + (int) $map[$nextRow][$nextCol],
                $nextRow, $nextCol,
                $dRow, $dCol,
                $steps + 1,
            ]);
        }
    }

    if ($steps >= MIN_STEPS_BEFORE_TURN_OR_STOP) {
        $previousDirection = [$dRow, $dCol];            // can't go this way next
        $oppositeDirection = [$dRow * -1, $dCol * -1];  // can't go this way next either
        foreach (DIRECTIONS as $nextDirection) {
            if ($nextDirection !== $previousDirection && $nextDirection !== $oppositeDirection) {
                [$dRow, $dCol] = $nextDirection;
                $nextRow = $row + $dRow;
                $nextCol = $col + $dCol;
                if ($nextRow >= 0 && $nextRow < $mapHeight && $nextCol >= 0 && $nextCol < $mapWidth) {
                    $priorityQueue->insert([
                        $heatLoss + (int) $map[$nextRow][$nextCol],
                        $nextRow, $nextCol,
                        $dRow, $dCol,
                        1
                    ]);
                }
            }
        }
    }
}
