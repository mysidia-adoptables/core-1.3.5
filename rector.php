<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/', // change to your code directory
    ]);

    $rectorConfig->skip([
        __DIR__ . '/vendor', // change to your code directory
    ]);


    // 🔨 Upgrade all the way from PHP 5.6 → 8.2
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_84,
    ]);


    // 🔨 Add real type declarations to match parent/interface
    $rectorConfig->rules([
        AddReturnTypeDeclarationBasedOnParentClassMethodRector::class,
    ]);
};
