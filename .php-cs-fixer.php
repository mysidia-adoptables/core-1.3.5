<?php
declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/',
    ])
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRules([
        'no_closing_tag' => true,
        // You can also add more fixers if you want to clean up other formatting
        '@PSR12' => true,           // example preset
    ])
    // Enable parallel processing to speed things up  [oai_citation:1‡cs.symfony.com](https://cs.symfony.com/doc/usage.html?utm_source=chatgpt.com)
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect());
