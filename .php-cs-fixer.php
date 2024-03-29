<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'public',
        'templates'
    ])->name('*.php')
;

$config = new PhpCsFixer\Config();
$config->setRiskyAllowed(true);
return $config->setRules([
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
])->setFinder($finder);