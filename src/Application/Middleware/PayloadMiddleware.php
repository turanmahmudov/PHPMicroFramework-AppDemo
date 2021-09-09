<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Framework\Http\Exception\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PayloadMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     * @throws BadRequestException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $content = json_decode($request->getBody()->getContents(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestException("Payload is not valid JSON");
        }

        $request = $request->withParsedBody($content);

        return $handler->handle($request);
    }
}