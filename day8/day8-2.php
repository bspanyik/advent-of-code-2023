<?php

$input = file_get_contents($argv[1] ?? 'input.txt');
if ($input === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$lines = explode("\n\n", $input);

$directions = str_split($lines[0]);
$map = [];
$nodes = [];
foreach (explode("\n", $lines[1]) as $row) {
    $keyNode = substr($row, 0, 3);
    if (str_ends_with($keyNode, 'A')) {
        $nodes[] = $keyNode;
    }
    $nodeLeft = substr($row, 7, 3);
    $nodeRight = substr($row, 12, 3);
    $map[$keyNode] = [
        'L' => [
            'node' => $nodeLeft,
            'endZ' => str_ends_with($nodeLeft, 'Z'),
        ],
        'R' => [
            'node' => $nodeRight,
            'endZ' => str_ends_with($nodeRight, 'Z'),
       ]
    ];
}

$countDirections = count($directions);
$z = [];
foreach ($nodes as $node) {
    $steps = 0;
    $isLoop = false;
    do {
        $direction = $directions[$steps % $countDirections];
        $next = $map[$node][$direction];
        if ($next['endZ']) {
            $isLoop = isset($z[$next['node']]);
            if (!$isLoop) {
                $z[$next['node']] = $steps + 1; // + 1 because xxA-nodes were step 1
            }
        }

        $node = $next['node'];
        $steps += 1;
    } while (!$isLoop);
}

$lcmFactors = [];
foreach ($z as $number) {
    foreach (getPrimes($number) as $factor => $exponent) {
        if (!isset($lcmFactors[$factor]) || $lcmFactors[$factor] < $exponent) {
            $lcmFactors[$factor] = $exponent;
        }
    }
}

$leastCommonMultiple = 1;
foreach ($lcmFactors as $factor => $exponent) {
    $leastCommonMultiple *= pow($factor, $exponent);
}

echo $leastCommonMultiple . PHP_EOL;

/** @return array<int, int> */
function getPrimes(int $number): array
{
    $primes = [];
    $maxDiv = floor(sqrt($number));
    $div = 2;
    while ($number > 1 && $div <= $maxDiv) {
        if ($number % $div === 0) {
            $primes[] = $div;
            $number = $number / $div;
        } else {
            $div += 1;
        }
    }

    if (count($primes) <= 1) {
        $primes[] = $number;
    }

    return array_count_values($primes);
}
