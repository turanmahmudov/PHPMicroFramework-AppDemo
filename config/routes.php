<?php

declare(strict_types=1);

use App\Application\Middleware\FilterMiddleware;
use App\Application\Middleware\PaginationMiddleware;
use App\Application\Middleware\PayloadMiddleware;
use Framework\Application;
use Psr\Container\ContainerInterface;

return static function (Application $app, ContainerInterface $container): void {
    $app->get('[/]', 'App\Application\Actions\Home\Help');

    // Token
    $app->post('/token[/]', 'App\Application\Actions\Token\Token')
        ->add(PayloadMiddleware::class);
    $app->get('/info[/]', function ($request, $response) {
        phpinfo();
    });

    // API
    $app->group('/api/v1', static function () use ($app): void {
        // Posts
        $app->get('/posts[/]', 'App\Application\Actions\Post\GetAll')
            ->add([
                PaginationMiddleware::class,
                FilterMiddleware::class
            ]);

        $app->get('/posts/{id:uuid}[/]', 'App\Application\Actions\Post\Get');

        $app->post('/posts[/]', 'App\Application\Actions\Post\Create')
            ->add(PayloadMiddleware::class);

        $app->put('/posts/{id:uuid}[/]', 'App\Application\Actions\Post\Update')
            ->add(PayloadMiddleware::class);

        $app->delete('/posts/{id:uuid}[/]', 'App\Application\Actions\Post\Delete')
            ->add([

            ]);
    });
};
