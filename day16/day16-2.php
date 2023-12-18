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

$startPositions = prepareStartPositions($maxRow, $maxCol);
$results = [];

$begin = microtime(true);
foreach ($startPositions as [$row, $col, $direction]) {
    $energized = [];
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

    $results[] = array_sum(array_map('count', $energized));
}

sort($results);

$end = microtime(true);
echo array_pop($results) . sprintf(' in %f seconds.', $end - $begin) . PHP_EOL;

/**
 * @return array<int, array<int, mixed>>
 */
function prepareStartPositions(int $maxRow, int $maxCol): array
{
    $start = [];

    $maxRow -= 1;
    $maxCol -= 1;

    for ($col = 0; $col <= $maxCol; $col++) {
        $start[] = [0, $col, 'S'];
    }

    for ($row = 0; $row <= $maxRow; $row++) {
        $start[] = [$row, $maxCol, 'W'];
    }

    for ($col = $maxCol; $col >= 0; $col--) {
        $start[] = [$maxRow, $col, 'N'];
    }

    for ($row = $maxRow; $row >= 0; $row--) {
        $start[] = [$row, 0, 'E'];
    }

    return $start;
}

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
