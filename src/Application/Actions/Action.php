<?php

declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface;

class Action
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    protected function respondWithJson(
        string $status,
        $data,
        int $code = 200
    ): ResponseInterface {
        $result = [
            'code' => $code,
            'status' => $status,
            'data' => $data,
        ];

        $this->response->getBody()->write(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($code)
        ;
    }
}
