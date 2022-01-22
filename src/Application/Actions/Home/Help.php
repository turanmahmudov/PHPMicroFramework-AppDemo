<?php

declare(strict_types=1);

namespace App\Application\Actions\Home;

use Psr\Http\Message\ResponseInterface;

class Help
{
    public function __invoke(ResponseInterface $response): ResponseInterface
    {
        $endpoints = [
            'posts' => '/api/v1/posts',
            'users' => '/api/v1/users',
            'info' => '/info',
            'token' => '/token',
            'help' => '/',
        ];

        $message = [
            'endpoints' => $endpoints,
            'version' => '1',
            'timestamp' => time(),
        ];

        $response->getBody()->write(json_encode($message, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}
