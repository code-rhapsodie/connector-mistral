<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->files()->name('*.php');

return (new PhpCsFixer\Config())
    ->setRules([
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'property' => 'one',
            ],
        ],
        'class_definition' => [
            'single_item_single_line' => true,
            'inline_constructor_arguments' => false,
        ],
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        $finder
    );

