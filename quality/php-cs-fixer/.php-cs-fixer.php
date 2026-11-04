<?php
/**
 * This file is part of the LongitudeOne Spatial core library.
 *
 * PHP 8.3 | 8.4 | 8.5
 *
 * Copyright LongitudeOne - Alexandre Tranchant.
 * Copyright 2026.
 *
 */

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

// Replace the value of this variable with the project's launch year.
const LO_FIRST_YEAR = 2026;

function __copyright(int $launchYear): string
{
    $currentYear = (int) date('Y');
    if ($currentYear === $launchYear) {
        return (string) $currentYear;
    }

    return sprintf('%d-%d', $launchYear, $currentYear);
}

$header = file_get_contents(__DIR__.'/headers.txt');
$header = str_replace('%year%', __copyright(LO_FIRST_YEAR), $header);

$finder = Finder::create()
    ->files()
    ->name('*.php')
    ->in([
        __DIR__.'/../../lib/',
        __DIR__.'/../../tests/',
    ])
    ->append([
        __FILE__,
    ])
;

$config = new Config();

return $config->setRules([
    '@Symfony' => true,
    '@Symfony:risky' => true,
    '@autoPHPMigration' => true,
    '@autoPHPMigration:risky' => true,
    '@autoPHPUnitMigration:risky' => true,
    'declare_strict_types' => true,
    'header_comment' => [
        'comment_type' => 'PHPDoc',
        'header' => $header,
        'location' => 'after_open',
        'separate' => 'bottom',
    ],
    //        'date_time_immutable' => true,
    'ordered_interfaces' => [
        'direction' => 'ascend',
        'order' => 'alpha',
    ],
    'ordered_class_elements' => [
        'order' => [
            'use_trait',
            'case',
            'constant_public', 'constant_protected', 'constant_private', 'constant',
            'property_public_static', 'property_protected_static', 'property_private_static', 'property_static',
            'property_public', 'property_protected', 'property_private', 'property',
            'construct', 'destruct',
            'phpunit',
            'method_public_static', 'method_protected_static', 'method_private_static', 'method_static',
            'method_public', 'method_protected', 'method_private', 'method', 'magic',
        ],
        'sort_algorithm' => 'alpha',
    ],
    'php_unit_test_case_static_method_calls' => [
        'call_type' => 'static',
        'target' => 'newest',
    ],
])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setCacheFile(__DIR__.'/.php_cs.cache')
;
