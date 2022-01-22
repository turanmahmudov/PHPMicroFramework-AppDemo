<?php

declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Domain\Exception\RepositoryException;
use App\Domain\Validation\PostValidator;
use Framework\Http\Exception\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Update extends PostAction
{
    protected array $scope = ['post.all', 'post.update'];
    protected string $errorMessage = 'Token not allowed to update posts';

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param PostValidator $postValidator
     * @param array $args
     * @return ResponseInterface
     * @throws RepositoryException
     * @throws BadRequestException
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        PostValidator $postValidator,
        array $args = []
    ): ResponseInterface {
        $uuid = $args['id'];

        $post = $this->postRepository->getById($uuid);

        $data = $request->getParsedBody() ?: [];

        $validation = $postValidator->validate($data);
        if (!$validation->isValid()) {
            throw new BadRequestException(implode(', ', $validation->getMessages()));
        }

        $postData = [
            'id' => $uuid,
            'title' => $data['title'],
            'content' => $data['content']
        ];
        $post = $this->hydrator->hydrate($postData, $post);
        $post->touch();
        $this->postRepository->update($post);

        $response->getBody()->write(json_encode($post, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Content-Location', '/api/v1/posts/'.$uuid);
    }
}