<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Capsule;

use Psr\Container\ContainerInterface;

abstract class AbstractRepository
{
    public function __construct(ContainerInterface $container)
    {
        if ($container->has('capsule')) {
            $capsule = $container->get('capsule');
            new $capsule;
        }
    }
}