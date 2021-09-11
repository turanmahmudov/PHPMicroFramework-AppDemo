<?php

declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Domain\Exception\PostNotFoundException;
use App\Domain\Exception\RepositoryException;
use App\Domain\Validation\PostValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Delete extends PostAction
{
    protected array $scope = ['post.all', 'post.delete'];
    protected string $errorMessage = 'Token not allowed to delete posts';

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param PostValidator $postValidator
     * @param array $args
     * @return ResponseInterface
     * @throws RepositoryException
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        PostValidator $postValidator,
        array $args = []
    ): ResponseInterface {
        $uuid = $args['id'];

        try {
            $post = $this->postRepository->getById($uuid);
        } catch (RepositoryException $exception) {
            throw $exception;
        }

        $this->postRepository->delete($post);

        return $response->withStatus(204);
    }
}