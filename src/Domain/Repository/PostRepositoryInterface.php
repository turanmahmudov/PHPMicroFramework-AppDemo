<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Post;
use App\Domain\Exception\RepositoryException;

interface PostRepositoryInterface
{
    /**
     * @param Post $post
     * @return void
     * @throws RepositoryException
     */
    public function save(Post $post): void;

    /**
     * @param Post $post
     * @return void
     * @throws RepositoryException
     */
    public function update(Post $post): void;

    /**
     * @param Post $post
     * @return void
     * @throws RepositoryException
     */
    public function delete(Post $post): void;

    /**
     * @param int $page
     * @param int $limit
     * @param string|null $filter
     * @return Post[]
     * @throws RepositoryException
     */
    public function getAll(int $page, int $limit, ?string $filter): array;

    /**
     * @param string $id
     * @return Post
     * @throws RepositoryException
     */
    public function getById(string $id): Post;
}