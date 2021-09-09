<?php

namespace App\Infrastructure\Persistence\Capsule;

use Psr\Container\ContainerInterface;

abstract class AbstractRepository
{
    public function __construct(ContainerInterface $container)
    {
        $container->make('capsule');
    }
}