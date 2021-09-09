<?php

declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Domain\Exception\RepositoryException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Get extends PostAction
{
    protected array $scope = ['post.all', 'post.read'];
    protected string $errorMessage = 'Token not allowed to read posts';

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = []): ResponseInterface
    {
        try {
            $post = $this->postRepository->getById($args['id']);
        } catch (RepositoryException $exception) {

        }

        $response->getBody()->write(json_encode($post, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}