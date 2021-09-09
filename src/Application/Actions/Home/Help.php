<?php

declare(strict_types=1);

namespace App\Application\Actions\Home;

use App\Application\Actions\Action;

class Help extends Action
{
    public function __invoke()
    {
        $endpoints = [
            'posts' => '/api/v1/posts',
            'users' => '/api/v1/users',
            'docs' => '/docs',
            'help' => '/',
        ];

        $message = [
            'endpoints' => $endpoints,
            'version' => '1',
            'timestamp' => time(),
        ];

        return $this->respondWithJson('success', $message, 200);
    }
}
