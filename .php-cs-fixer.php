<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/routes',
        __DIR__ . '/database',
        __DIR__ . '/config',
        __DIR__ . '/resources',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setRules([
        '@PSR12' => true,                  // Estándar PSR-12
        'array_syntax' => ['syntax' => 'short'], // [] en lugar de array()
        'no_unused_imports' => true,       // Quita imports no usados
        'single_quote' => true,            // Comillas simples por defecto
        'binary_operator_spaces' => [
            'operators' => ['=>' => 'align_single_space']
        ],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => [
            'statements' => ['return']
        ],
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => ['space' => 'single'],
        'line_ending' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'trim_array_spaces' => true,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_scalar' => true,
        'phpdoc_trim' => true,
        'phpdoc_no_empty_return' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
