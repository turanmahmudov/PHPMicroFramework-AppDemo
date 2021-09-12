<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Memory;

use App\Domain\Entity\Post;
use App\Domain\Exception\PostNotFoundException;
use App\Domain\Repository\PostRepositoryInterface;
use Laminas\Hydrator\HydratorInterface;

class InMemoryPostRepository implements PostRepositoryInterface
{
    /**
     * @var HydratorInterface
     */
    protected HydratorInterface $hydrator;

    /**
     * @var array<Post>
     */
    protected array $posts = [];

    public function save(Post $post): void
    {
        $this->posts[(string) $post->getId()] = $post;
    }

    public function update(Post $post): void
    {
        $this->posts[(string) $post->getId()] = $post;
    }

    public function delete(Post $post): void
    {
        $id = (string) $post->getId();
        unset($this->posts[$id]);
    }

    public function getAll(int $page = 1, int $limit = 10, ?string $filter = null): array
    {
        return array_values($this->posts);
    }

    public function getById(string $id): Post
    {
        if (isset($this->posts[$id])) {
            return $this->posts[$id];
        }
        throw new PostNotFoundException();
    }
}