<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Domain\Exception\PostNotFoundException;
use App\Domain\Exception\RepositoryException;
use Framework\Http\Exception;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class ErrorMiddleware implements MiddlewareInterface
{
    protected ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Exception $exception) {
            $result = [
                'code' => $exception->getStatusCode(),
                'status' => $exception->getMessage(),
                'data' => [],
            ];

            $response = $this->responseFactory->createResponse($exception->getStatusCode(), $exception->getMessage());
            $response->getBody()->write(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (PostNotFoundException $exception) {
            $result = [
                'code' => 404,
                'status' => $exception->getMessage(),
                'data' => [],
            ];

            $response = $this->responseFactory->createResponse(404, $exception->getMessage());
            $response->getBody()->write(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (RepositoryException $exception) {
            $result = [
                'code' => 500,
                'status' => $exception->getMessage(),
                'data' => [],
            ];

            $response = $this->responseFactory->createResponse(500, $exception->getMessage());
            $response->getBody()->write(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (Throwable $exception) {
            $message = $exception->getMessage();
            $code = $exception->getCode();
            $file = $exception->getFile();
            $line = $exception->getLine();
            $trace = $exception->getTraceAsString();
            $date = date('M d, Y G:iA');

            $logMessage = "<h3>Exception information:</h3>\n
           <p><strong>Message:</strong> {$message}</p>\n
           <p><strong>Code:</strong> {$code}</p>\n
           <p><strong>File:</strong> {$file}</p>\n
           <p><strong>Line:</strong> {$line}</p>\n
           <h3>Stack trace:</h3>\n
           <pre>{$trace}</pre>\n
           <hr />\n";

            $response = $this->responseFactory->createResponse(500, $message);
            $response->getBody()->write($logMessage);

            return $response;
        }
    }
}
