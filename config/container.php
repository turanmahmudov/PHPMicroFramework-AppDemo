<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Framework\Factory\ContainerFactory;

$repositories = require __DIR__.'/../config/repositories.php';
$dependencies = require __DIR__.'/../config/dependencies.php';

$containerConfig = array_merge($dependencies, $repositories);

try {
    try {
        Dotenv::createUnsafeImmutable(__DIR__.'/../')->load();
    } catch (\Dotenv\Exception\InvalidPathException $e) {
    }

    return (new ContainerFactory())($containerConfig);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
