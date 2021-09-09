<?php

declare(strict_types=1);

use Framework\Application;
use Psr\Container\ContainerInterface;

require __DIR__ . '/../vendor/autoload.php';

(function() {
    /** @var ContainerInterface */
    $container = require __DIR__ . '/../config/container.php';

    /** @var Application */
    $app = $container->get(Application::class);

    // Middlewares
    (require __DIR__ . '/../config/pipeline.php')($app, $container);

    // Routes
    (require __DIR__ . '/../config/routes.php')($app, $container);

    $app->run();
})();