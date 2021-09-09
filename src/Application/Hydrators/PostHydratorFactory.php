<?php

declare(strict_types=1);

namespace App\Application\Hydrators;

use Laminas\Hydrator\HydratorInterface;
use Laminas\Hydrator\NamingStrategy\MapNamingStrategy;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\ClosureStrategy;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

class PostHydratorFactory
{
    public function create(): HydratorInterface
    {
        $hydrator = new ReflectionHydrator();
        $hydrator->setNamingStrategy(MapNamingStrategy::createFromHydrationMap([
            'created_at' => 'createdAt',
            'updated_at' => 'updatedAt'
        ]));
        $hydrator->addStrategy(
            'createdAt',
            new DateTimeFormatterStrategy('Y-m-d H:i:s')
        );
        $hydrator->addStrategy(
            'updatedAt',
            new DateTimeFormatterStrategy('Y-m-d H:i:s')
        );

        $hydrator->addStrategy(
            'id',
            new ClosureStrategy(
                function ($uuid) {
                    return (string) $uuid;
                },
                function ($uuid) {
                    return Uuid::fromString((string) $uuid);
                }
            )
        );

        return $hydrator;
    }
}