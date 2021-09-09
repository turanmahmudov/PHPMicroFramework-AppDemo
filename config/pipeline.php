<?php

declare(strict_types=1);

use App\Application\Middleware\ErrorMiddleware;
use App\Application\Middleware\NotFoundMiddleware;
use Framework\Application;
use Framework\Router\DispatchMiddleware;
use Framework\Router\RouterMiddleware;
use Psr\Container\ContainerInterface;

return static function (Application $app, ContainerInterface $container): void {
    $app->add(ErrorMiddleware::class);

    $app->add(RouterMiddleware::class);

    $app->add(new \Tuupola\Middleware\HttpBasicAuthentication([
        'path' => '/token',
        'relaxed' => ['127.0.0.1', 'localhost'],
        'error' => function ($response, $args) {
            return new \Framework\Http\Exception\UnauthorizedException();
        },
        'users' => [
            'test' => 'test'
        ]
    ]));

    $app->add(new \Tuupola\Middleware\JwtAuthentication([
        'path' => '/api/',
        'ignore' => ['/token', '/info'],
        'secret' => getenv('JWT_SECRET'),
        'attribute' => false,
        'relaxed' => ['127.0.0.1', 'localhost'],
        'error' => function ($response, $args) {
            return new \Framework\Http\Exception\UnauthorizedException();
        },
        'before' => function ($request, $args) use ($container) {
            $container->get('token')->populate($args['decoded']);
        }
    ]));

    $app->add(DispatchMiddleware::class);

    $app->add(NotFoundMiddleware::class);
};
