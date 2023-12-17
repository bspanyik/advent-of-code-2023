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
    $horizontalValue = findHorizontalReflection($map);
    if ($horizontalValue) {
        $sum += $horizontalValue * 100;
    } else {
        $sum += findVerticalReflection($map);
    }
}

echo $sum . PHP_EOL;

/** @param array<int, string> $map */
function findHorizontalReflection(array $map): int
{
    $mapCount = count($map);
    for ($i = 1; $i < $mapCount; $i++) {
        if ($map[$i] === $map[$i - 1]) {
            if ($i === 1) {
                return 1;
            }

            $isMirror = true;
            for ($j = 2; $j <= $i && $i + $j - 1 < $mapCount; $j++) {
                if ($map[$i - $j] !== $map[$i + $j - 1]) {
                    $isMirror = false;
                    break;
                }
            }

            if ($isMirror) {
                return $i;
            }
        }
    }

    return 0;
}

/** @param array<int, string> $map */
function findVerticalReflection(array $map): int
{
    // transpose columns to rows
    $map = array_map(
        'implode',
        array_map(
            null,
            ...array_map('str_split', $map)
        )
    );

    return findHorizontalReflection($map);
}
