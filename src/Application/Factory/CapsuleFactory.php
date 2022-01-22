<?php

declare(strict_types=1);

namespace App\Application\Factory;

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;

class CapsuleFactory
{
    public function __invoke(ContainerInterface $container): Capsule
    {
        $dbConfig = $container->get('config')['db'];
        $connectionConfig = $dbConfig['connections'][$dbConfig['default']];

        $capsule = new Capsule();

        $capsule->addConnection($connectionConfig);
        $capsule->setAsGlobal();

        return $capsule;
    }
}
