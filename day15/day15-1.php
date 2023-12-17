<?php

declare(strict_types=1);

$inputFile = $argv[1] ?? 'input-test.txt';
if (!file_exists($inputFile)) {
    die(sprintf('The input file (%s) does not exist.' . PHP_EOL, $inputFile));
}

$input = file_get_contents($inputFile);
if ($input === false) {
    die(sprintf('Unable to load input file (%s)', $inputFile));
}

$input = explode(',', $input);

$sum = 0;
foreach ($input as $text) {
    $sum += hash($text);
}

echo $sum . PHP_EOL;

// Holiday Ascii String Helper algorithm :D
function hash(string $string): int
{
    $currentValue = 0;
    foreach (str_split($string) as $char) {
        $currentValue += ord($char);
        $currentValue *= 17;
        $currentValue %= 256;
    }

    return $currentValue;
}
