<?php

declare(strict_types=1);

const MIRRORS = [
    '/'  => ['N' => 'E', 'E' => 'N', 'W' => 'S', 'S' => 'W'],
    '\\' => ['N' => 'W', 'E' => 'S', 'W' => 'N', 'S' => 'E'],
];

$inputFile = $argv[1] ?? 'input.txt';
if (!file_exists($inputFile)) {
    die(sprintf('The input file (%s) does not exist.' . PHP_EOL, $inputFile));
}

$map = file($inputFile, FILE_IGNORE_NEW_LINES);
if ($map === false) {
    die(sprintf('Unable to load input file (%s)', $inputFile));
}

$map = array_map('str_split', $map);
$maxRow = count($map);
$maxCol = count($map[0]);

$energized = [];
$row = 0;
$col = 0;
$direction = 'E';
while (true) {
    if ($row < 0 || $row === $maxRow || $col < 0 || $col === $maxCol) {
        $direction = '';
    } elseif (!isset($energized[$row][$col])) {
        $energized[$row][$col] = [];
    } elseif (in_array($direction, $energized[$row][$col])) {
        $direction = '';
    }

    if ($direction === '') {
        if (empty($rays)) {
            break;
        }

        [$row, $col, $direction] = array_pop($rays);
        continue;
    }

    $energized[$row][$col][] = $direction;

    $cell = $map[$row][$col];
    switch ($cell) {
        case '.':
            $nextDirection = $direction;
            break;

        case '/':
        case '\\':
            $nextDirection = MIRRORS[$cell][$direction];
            break;

        case '-':
            if ($direction === 'N' || $direction === 'S') {
                $nextDirection = 'W';
                $rays[] = [$row, $col + 1, 'E'];
                $energized[$row][$col][] = $direction === 'N' ? 'S' : 'N';
            } else {
                $nextDirection = $direction;
            }
            break;

        case '|':
            if ($direction === 'E' || $direction === 'W') {
                $nextDirection = 'N';
                $rays[] = [$row + 1, $col, 'S'];
                $energized[$row][$col][] = $direction === 'E' ? 'W' : 'E';
            } else {
                $nextDirection = $direction;
            }
            break;

        default: die('Invalid cell.');
    }

    $row = nextRow($row, $nextDirection);
    $col = nextCol($col, $nextDirection);
    $direction = $nextDirection;
}

echo array_sum(array_map('count', $energized)) . PHP_EOL;

function nextRow(int $row, string $direction): int
{
    return match ($direction) {
        'N' => $row - 1,
        'S' => $row + 1,
        default => $row,
    };
}

function nextCol(int $col, string $direction): int
{
    return match ($direction) {
        'W' => $col - 1,
        'E' => $col + 1,
        default => $col,
    };
}
