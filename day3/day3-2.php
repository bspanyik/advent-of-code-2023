<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$lines = array_map('str_split', $lines);

$sum = 0;
foreach ($lines as $row => $line) {
    foreach ($line as $col => $char) {
        if ($char !== '*') {
            continue;
        }

        $gears = [];

        $charAbove = $lines[$row - 1][$col] ?? '.';
        if (is_numeric($charAbove)) {
            $gears[] = getDigitsFromLeft($row - 1, $col) . $charAbove . getDigitsFromRight($row - 1, $col);
        } else {
            $charAbovePrevious = $lines[$row - 1][$col - 1] ?? '.';
            if (is_numeric($charAbovePrevious)) {
                $gears[] = getDigitsFromLeft($row - 1, $col - 1) . $charAbovePrevious;
            }

            $charAboveNext = $lines[$row - 1][$col + 1] ?? '.';
            if (is_numeric($charAboveNext)) {
                $gears[] = $charAboveNext . getDigitsFromRight($row - 1, $col + 1);
            }
        }

        $charLeft = $line[$col - 1] ?? '.';
        if (is_numeric($charLeft)) {
            $gears[] = getDigitsFromLeft($row, $col - 1) . $charLeft;
        }

        $charRight = $line[$col + 1] ?? '.';
        if (is_numeric($charRight)) {
            $gears[] = $charRight . getDigitsFromRight($row, $col + 1);
        }

        $charBelow = $lines[$row + 1][$col] ?? '.';
        if (is_numeric($charBelow)) {
            $gears[] = getDigitsFromLeft($row + 1, $col) . $charBelow . getDigitsFromRight($row + 1, $col);
        } else {
            $charBelowPrevious = $lines[$row + 1][$col - 1] ?? '.';
            if (is_numeric($charBelowPrevious)) {
                $gears[] = getDigitsFromLeft($row + 1, $col - 1) . $charBelowPrevious;
            }

            $charBelowNext = $lines[$row + 1][$col + 1] ?? '.';
            if (is_numeric($charBelowNext)) {
                $gears[] = $charBelowNext . getDigitsFromRight($row + 1, $col + 1);
            }
        }

        if (count($gears) === 2) {
            $sum += (int) $gears[0] * (int) $gears[1];
        }
    }
}

echo $sum . PHP_EOL;

function getDigitsFromLeft(int $row, int $col): string
{
    global $lines;

    $digits = '';
    $i = 1;
    do {
        $previousChar = $lines[$row][$col - $i] ?? '.';
        if (is_numeric($previousChar)) {
            $digits = $previousChar . $digits;
        }
        $i += 1;
    } while (is_numeric($previousChar));

    return $digits;
}

function getDigitsFromRight(int $row, int $col): string
{
    global $lines;

    $digits = '';
    $i = 1;
    do {
        $nextChar = $lines[$row][$col + $i] ?? '.';
        if (is_numeric($nextChar)) {
            $digits = $digits . $nextChar;
        }
        $i += 1;
    } while (is_numeric($nextChar));

    return $digits;
}
