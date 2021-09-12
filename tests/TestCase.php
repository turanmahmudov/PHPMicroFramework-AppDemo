<?php

declare(strict_types=1);

namespace App\Tests;

use Framework\Application;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\Uri;
use Psr\Container\ContainerInterface;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function getApp(): Application
    {
        /** @var ContainerInterface */
        $container = require __DIR__ . '/../config/container.php';

        /** @var Application */
        $app = $container->get(Application::class);

        // Middlewares
        (require __DIR__ . '/../config/pipeline.php')($app, $container);

        // Routes
        (require __DIR__ . '/../config/routes.php')($app, $container);

        return $app;
    }

    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): ServerRequest {
        $uri = (new Uri(''))->withPort(80)->withPath($path);

        $stream = (new StreamFactory())->createStreamFromResource(fopen('php://temp', 'w+'));

        return new ServerRequest(
            $serverParams,
            [],
            $uri,
            $method,
            $stream,
            $headers,
            $cookies
        );
    }
}