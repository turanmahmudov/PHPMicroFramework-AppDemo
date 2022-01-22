<?php

declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Domain\Exception\RepositoryException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetAll extends PostAction
{
    protected array $scope = ['post.all', 'post.list'];
    protected string $errorMessage = 'Token not allowed to list posts';

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws RepositoryException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $page = $request->getAttribute('page');
        $limit = $request->getAttribute('limit');
        $filter = $request->getAttribute('filter');

        $posts = $this->postRepository->getAll($page, $limit, $filter);

        $response->getBody()->write(json_encode($posts, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}