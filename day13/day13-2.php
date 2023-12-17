<?php

declare(strict_types=1);

$inputFile = $argv[1] ?? 'input.txt';
if (!file_exists($inputFile)) {
    die(sprintf('The input file (%s) does not exist.' . PHP_EOL, $inputFile));
}

$input = file_get_contents($inputFile);
if ($input === false) {
    die(sprintf('Unable to load input file (%s)', $inputFile));
}

// because input files may come from Windows
$input = str_replace("\r", "", $input);

$input = array_map(
    fn(string $block) => explode("\n", $block),
    explode("\n\n", $input)
);

$sum = 0;
foreach ($input as $map) {
    $map = array_map('str_split', $map);
    $horizontalValue = findHorizontalReflection($map);
    if ($horizontalValue) {
        $sum += $horizontalValue * 100;
    } else {
        $sum += findVerticalReflection($map);
    }
}

echo $sum . PHP_EOL;

/** @param array<int, array<int, string>> $map */
function findHorizontalReflection(array $map): int
{
    $mapCount = count($map);
    for ($i = 1; $i < $mapCount; $i++) {
        $diff = countDiff($map[$i - 1], $map[$i]);
        if ($diff > 1) {
            continue;
        }

        $isMirror = true;

        // consider smudge fixed, rest should be a mirror
        if ($diff === 1) {
            for ($j = 2; $j <= $i && $i + $j - 1 < $mapCount; $j++) {
                if ($map[$i - $j] !== $map[$i + $j - 1]) {
                    $isMirror = false;
                    break;
                }
            }

            if ($isMirror) {
                return $i;
            }

            continue;
        }

        // no smudge yet, fix it while checking rest of the rows
        $smudgeFixed = false;
        for ($j = 2; $j <= $i && $i + $j - 1 < $mapCount; $j++) {
            $diff = countDiff($map[$i - $j], $map[$i + $j - 1]);
            if ($diff > 1) {
                $isMirror = false;
                break;
            }

            if ($diff === 1) {
                if (!$smudgeFixed) {
                    $smudgeFixed = true;
                } else {
                    $isMirror = false;
                    break;
                }
            }
        }

        // there must be a smudge on it to be valid
        if ($isMirror && $smudgeFixed) {
            return $i;
        }
    }

    return 0;
}

/** @param array<int, array<int, string>> $map */
function findVerticalReflection(array $map): int
{
    $map = array_map(null, ...$map);

    return findHorizontalReflection($map);
}

/**
 * @param array<int, string> $a
 * @param array<int, string> $b
 */
function countDiff(array $a, array $b): int
{
    $diff = 0;
    for ($i = 0; $i < count($a); $i++) {
        if ($a[$i] !== $b[$i]) {
            $diff += 1;
            if ($diff > 1) {
                return 2;
            }
        }
    }

    return $diff;
}
