<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Basic\SingleLineEmptyBodyFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/day1',
        __DIR__ . '/day2',
        __DIR__ . '/day3',
    ]);

    // this way you add a single rule
    $ecsConfig->rules([
        MethodArgumentSpaceFixer::class,
        SingleLineEmptyBodyFixer::class,
    ]);

    $ecsConfig->rulesWithConfiguration([
        ConcatSpaceFixer::class => ['spacing' => 'one'],
        FunctionDeclarationFixer::class => ['closure_fn_spacing' => 'none'],
        BinaryOperatorSpacesFixer::class => ['operators' => ['=>' => 'align_single_space_minimal']],
    ]);

    // this way you can add sets - group of rules
    $ecsConfig->sets([
        // run and fix, one by one
        // SetList::SPACES,
        // SetList::ARRAY,
        // SetList::DOCBLOCK,
        // SetList::NAMESPACES,
        // SetList::COMMENTS,
        SetList::PSR_12,
        // SetList::LARAVEL,
    ]);
};
