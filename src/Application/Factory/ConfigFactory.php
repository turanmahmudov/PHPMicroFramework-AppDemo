<?php

declare(strict_types=1);

namespace App\Application\Factory;

use Noodlehaus\Config;
use Noodlehaus\Parser\Php;

class ConfigFactory
{
    public function __invoke(): Config
    {
        return new Config(__DIR__.'/../Config', new Php());
    }
}
