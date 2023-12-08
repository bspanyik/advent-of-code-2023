<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$seeds = array_map(
    callback: 'intval',
    array: explode(
        separator: ' ',
        string: str_replace('seeds: ', '', $lines[0])
    )
);

$seedRanges = [];
for ($i = 0; $i < count($seeds); $i += 2) {
    $seedRanges[] = [
        (int) $seeds[$i],
        (int) $seeds[$i] + (int) $seeds[$i + 1] - 1,
    ];
}

$lineCount = count($lines);
$currentLine = 3;
$almanac = [];
while ($currentLine < $lineCount) {
    $section = [];
    while ($currentLine < $lineCount && $lines[$currentLine] !== '') {
        [$destination, $source, $range] = array_map(
            callback: 'intval',
            array: explode(' ', $lines[$currentLine])
        );

        $section[] = [
            'start' => $source,
            'end'   => $source + $range - 1,
            'diff'  => $destination - $source,
        ];

        $currentLine += 1;
    }

    usort($section, fn(array $a, array $b) => $a['start'] <=> $b['start']);

    // add 0 if missing from the front of the section
    if ($section[0]['start'] > 0) {
        array_unshift($section, [
            'start' => 0,
            'end'   => $section[0]['start'] - 1,
            'diff'  => 0,
        ]);
    }

    // fill-up the holes if there's any in the section
    $previousRow = $section[0];
    for ($i = 1; $i < count($section); $i++) {
        $row = $section[$i];
        if ($row['start'] - $previousRow['end'] > 1) {
            array_splice($section, $i, 0, [
                [
                    'start' => $previousRow['end'] + 1,
                    'end'   => $row['start'] - 1,
                    'diff'  => 0,
                ]
            ]);
        }
        $previousRow = $row;
    }

    $almanac[] = $section;
    $currentLine += 2;
}

foreach ($almanac as $map) {
    $newSeedRanges = [];
    foreach ($seedRanges as [$first, $last]) {
        $firstRow = findSeedInMap($first, $map);
        $lastRow = findSeedInMap($last, $map);

        echo $first . ' [' . ($firstRow !== false ? $firstRow : 'false') . '] | ' . $last . ' [' . ($lastRow !== false ? $lastRow : 'false') . ']' . PHP_EOL;

        if ($firstRow === $lastRow) {
            if ($firstRow !== false) {
                $row = $map[$firstRow];
                $first += $row['diff'];
                $last += $row['diff'];
            }
            $newSeedRanges[] = [$first, $last];
        } else {
            $row = $map[$firstRow];
            $newFirst = $first + $row['diff'];
            $newLast = $row['end'] + $row['diff'];
            $newSeedRanges[] = [$newFirst, $newLast];

            if ($lastRow !== false) {
                for ($i = $firstRow + 1; $i <= $lastRow; $i++) {
                    $row = $map[$i];
                    $newFirst = $row['start'] + $row['diff'];
                    if ($i < $lastRow) {
                        $newLast = $row['end'] + $row['diff'];
                    } else {
                        $newLast = $last + $row['diff'];
                    }
                    $newSeedRanges[] = [$newFirst, $newLast];
                }
            } else {
                for ($i = $firstRow + 1; $i < count($map); $i++) {
                    $row = $map[$i];
                    $newFirst = $row['start'] + $row['diff'];
                    $newLast = $row['end'] + $row['diff'];
                    $newSeedRanges[] = [$newFirst, $newLast];
                }
                $newSeedRanges[] = [$row['end'], $last];
            }
        }
    }

    $seedRanges = $newSeedRanges;
}

$locations = [];
foreach ($seedRanges as [$first, $last]) {
    $locations[] = $first;
    $locations[] = $last;
}
sort($locations);

echo $locations[0] . PHP_EOL;

/**
 * @param int $seed
 * @param array<array{'end': int}> $map
 * @return int|false
 */
function findSeedInMap(int $seed, array $map): int|false
{
    foreach ($map as $rowIndex => $row) {
        if ($seed < $row['end']) {
            return $rowIndex;
        }
    }

    return false;
}
