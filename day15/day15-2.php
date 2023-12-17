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

$input = explode(',', $input);

$boxes = [];
foreach ($input as $text) {
    if (str_contains($text, '=')) {
        [$label,$lens] = explode('=', $text);
        $boxNumber = theHolidayASCIIStringHelper($label);
        $boxes[$boxNumber][$label] = (int) $lens;
    } else {
        $label = rtrim($text, '-');
        $boxNumber = theHolidayASCIIStringHelper($label);
        unset($boxes[$boxNumber][$label]);
    }
}

$focusingPower = 0;
foreach ($boxes as $boxNumber => $box) {
    $position = 1;
    foreach ($box as $lens) {
        $focusingPower += ($boxNumber + 1) * $position * $lens;
        $position += 1;
    }
}

echo $focusingPower . PHP_EOL;

function theHolidayASCIIStringHelper(string $string): int
{
    $currentValue = 0;
    foreach (str_split($string) as $char) {
        $currentValue += ord($char);
        $currentValue *= 17;
        $currentValue %= 256;
    }

    return $currentValue;
}
