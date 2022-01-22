<?php

declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Domain\Entity\Post;
use App\Domain\Exception\RepositoryException;
use App\Domain\Validation\PostValidator;
use DateTime;
use Framework\Http\Exception\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use ReflectionClass;
use ReflectionException;

class Create extends PostAction
{
    protected array $scope = ['post.all', 'post.create'];
    protected string $errorMessage = 'Token not allowed to create posts';

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param PostValidator $postValidator
     * @return ResponseInterface
     * @throws RepositoryException
     * @throws BadRequestException|ReflectionException
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        PostValidator $postValidator
    ): ResponseInterface {
        $data = $request->getParsedBody() ?: [];

        $validation = $postValidator->validate($data);
        if (!$validation->isValid()) {
            throw new BadRequestException(implode(', ', $validation->getMessages()));
        }

        $uuid = Uuid::uuid4();
        $createdAt = (new DateTime())->format('Y-m-d H:i:s');
        $postData = [
            'id' => $uuid,
            'title' => $data['title'],
            'content' => $data['content'],
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ];
        $post = $this->hydrator->hydrate(
            $postData,
            (new ReflectionClass(Post::class))->newInstanceWithoutConstructor()
        );
        $this->postRepository->save($post);

        $response->getBody()->write(json_encode($post, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Content-Location', '/api/v1/posts/'.$uuid);
    }
}