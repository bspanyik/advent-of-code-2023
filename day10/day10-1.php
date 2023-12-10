<?php

declare(strict_types=1);

const PIPE_ITEMS = ['F', '-', '7', '|', 'J', 'L'];

// current-item => [ coming-from-here => can-only-go-this-way ]
const PIPE_ITEMS_TO_DIRECTIONS = [
    'F' => ['N' => 'E', 'W' => 'S'],
    '-' => ['E' => 'E', 'W' => 'W'],
    '7' => ['N' => 'W', 'E' => 'S'],
    '|' => ['N' => 'N', 'S' => 'S'],
    'J' => ['E' => 'N', 'S' => 'W'],
    'L' => ['W' => 'N', 'S' => 'E'],
];

const VALID_PIPE_ITEMS_BY_DIRECTION = [
    'N' => ['|', 'F', '7'],
    'W' => ['-', 'F', 'L'],
    'E' => ['-', '7', 'J'],
    'S' => ['|', 'L', 'J'],
];

$map = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($map === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$map = array_map('str_split', $map);
$lastRow = count($map) - 1;
$lastCol = count($map[0]) - 1;

foreach ($map as $row => $line) {
    foreach ($line as $col => $char) {
        if ($char === 'S') {
            break 2;
        }
    }
}

if (!isset($row) || !isset($col)) {
    die('Starting position was not found. WTF?!');
}

$possibleS = determineS($row, $col);
if (count($possibleS) > 1) {
    die('We are unprepared for multiple possible loops.');
}

$pipeItem = $possibleS[0];

$step = 0;
$direction = array_key_first(PIPE_ITEMS_TO_DIRECTIONS[$pipeItem]);
while ($pipeItem != 'S') {
    $step += 1;

    $direction = PIPE_ITEMS_TO_DIRECTIONS[$pipeItem][$direction];
    match ($direction) {
        'N' => $row -= 1,
        'W' => $col -= 1,
        'E' => $col += 1,
        'S' => $row += 1,
    };

    $pipeItem = $map[$row][$col];
}

echo floor($step / 2) . PHP_EOL;

/**
 * @return string[]
 */
function determineS(int $row, int $col): array
{
    $possibleS = [];
    $possible = false;
    foreach (PIPE_ITEMS as $pipeItem) {
        if (!isValidInPosition($pipeItem, $row, $col)) {
            continue;
        }

        foreach (PIPE_ITEMS_TO_DIRECTIONS[$pipeItem] as $direction) {
            $possible = match ($direction) {
                'N' => isValidNorth($row, $col),
                'W' => isValidWest($row, $col),
                'E' => isValidEast($row, $col),
                'S' => isValidSouth($row, $col),
            };

            if (!$possible) {
                break;
            }
        }

        if ($possible) {
            $possibleS[] = $pipeItem;
        }
    }

    return $possibleS;
}

function isValidInPosition(string|int $pipeItem, int $row, int $col): bool
{
    global $lastRow, $lastCol;

    if ($row === 0) {
        if ($col === 0) {
            return $pipeItem === 'F';
        }

        if ($col === $lastCol) {
            return $pipeItem === '7';
        }

        return in_array($pipeItem, ['F', '-', '7']);
    }

    if ($row === $lastRow) {
        if ($col === 0) {
            return $pipeItem === 'L';
        }

        if ($col === $lastCol) {
            return $pipeItem === 'J';
        }

        return in_array($pipeItem, ['L', '-', 'J']);
    }

    if ($col === 0) {
        return in_array($pipeItem, ['F', '|', 'L']);
    }

    if ($col === $lastCol) {
        return in_array($pipeItem, ['7', '|', 'J']);
    }

    return true;
}

function isValidNorth(int $row, int $col): bool
{
    global $map;

    return in_array($map[$row - 1][$col] ?? null, VALID_PIPE_ITEMS_BY_DIRECTION['N']);
}

function isValidWest(int $row, int $col): bool
{
    global $map;

    return in_array($map[$row][$col - 1] ?? null, VALID_PIPE_ITEMS_BY_DIRECTION['W']);
}

function isValidEast(int $row, int $col): bool
{
    global $map;

    return in_array($map[$row][$col + 1] ?? null, VALID_PIPE_ITEMS_BY_DIRECTION['E']);
}

function isValidSouth(int $row, int $col): bool
{
    global $map;

    return in_array($map[$row + 1][$col] ?? null, VALID_PIPE_ITEMS_BY_DIRECTION['S']);
}
