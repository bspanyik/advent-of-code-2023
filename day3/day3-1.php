<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$lines = array_map('str_split', $lines);

$sum = 0;
$inNumber = false;
$isPartNumber = false;
$number = 0;
foreach ($lines as $row => $line) {
    foreach ($line as $col => $char) {
        if (is_numeric($char)) {
            if (!$inNumber) {
                $inNumber = true;
                $number = (int) $char;
                $isPartNumber = checkAboveMiddleBelow($row, $col - 1);
            } else {
                $number = $number * 10 + (int) $char;
            }

            if (!$isPartNumber) {
                $isPartNumber = checkAboveMiddleBelow($row, $col);
            }
        } else {
            if ($inNumber) {
                $inNumber = false;

                if (!$isPartNumber) {
                    $isPartNumber = checkAboveMiddleBelow($row, $col);
                }

                if ($isPartNumber) {
                    $sum += $number;
                }
            }
        }
    }

    if ($inNumber) {
        $inNumber = false;
        if ($isPartNumber) {
            $sum += $number;
        }
    }
}

echo $sum . PHP_EOL;

function checkAboveMiddleBelow(int $row, int $col): bool
{
    global $lines;

    $char = $lines[$row - 1][$col] ?? '.';
    if (isSymbol($char)) {
        return true;
    }

    $char = $lines[$row][$col] ?? '.';
    if (isSymbol($char)) {
        return true;
    }

    $char = $lines[$row + 1][$col] ?? '.';
    if (isSymbol($char)) {
        return true;
    }

    return false;
}

function isSymbol(string $char): bool
{
    return $char !== '.' && !is_numeric($char);
}
