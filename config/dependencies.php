<?php

declare(strict_types=1);

use App\Application\Factory\CapsuleFactory;
use App\Application\Factory\ConfigFactory;
use App\Domain\Token;
use function DI\factory;

return [
    'config' => factory(ConfigFactory::class),
    'capsule' => factory(CapsuleFactory::class),
    'token' => function() {
        return new Token([]);
    }
];
