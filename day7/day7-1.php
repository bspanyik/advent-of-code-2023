<?php

declare(strict_types=1);

const HIGH_CARD = 1;
const ONE_PAIR = 2;
const TWO_PAIRS = 3;
const THREE_OF_A_KIND = 4;
const FULL_HOUSE = 5;
const FOUR_OF_A_KIND = 6;
const FIVE_OF_A_KIND = 7;

const CARD_REPLACE_FROM = ['A', 'K', 'Q', 'J', 'T'];
const CARD_REPLACE_WITH = ['Z', 'Y', 'X', 'W', 'V'];

$input = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die('There\'s something wrong with the input file. I don\'t know what it is.' . PHP_EOL);
}

$hands = [];
foreach ($input as $line) {
    [$cards, $bet] = explode(' ', $line);
    $cards = prepareCards($cards);
    $hands[] = [
        'cards' => $cards,
        'bet' => (int) $bet,
        'type' => getTypeFromCards($cards),
    ];
}

usort($hands, 'sortHands');

$total = 0;
foreach ($hands as $key => $hand) {
    $total += ($key + 1) * $hand['bet'];
}

echo $total . PHP_EOL;

function getTypeFromCards(string $cards): int
{
    $values = array_values(array_count_values(str_split($cards)));
    rsort($values);

    return match ($values[0]) {
        5 => FIVE_OF_A_KIND,
        4 => FOUR_OF_A_KIND,
        3 => $values[1] === 2 ? FULL_HOUSE : THREE_OF_A_KIND,
        2 => $values[1] === 2 ? TWO_PAIRS : ONE_PAIR,
        default => HIGH_CARD
    };
}

/**
 * @param array{'cards': string, 'type': int} $hand1
 * @param array{'cards': string, 'type': int} $hand2
 * @return int
 */
function sortHands(array $hand1, array $hand2): int
{
    if ($hand1['type'] === $hand2['type']) {
        return $hand1['cards'] <=> $hand2['cards'];
    }

    return $hand1['type'] <=> $hand2['type'];
}

function prepareCards(string $cards): string
{
    return str_replace(CARD_REPLACE_FROM, CARD_REPLACE_WITH, $cards);
}
