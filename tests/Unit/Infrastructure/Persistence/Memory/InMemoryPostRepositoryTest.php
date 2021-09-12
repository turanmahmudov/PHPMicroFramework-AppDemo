<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Persistence\Memory;

use App\Domain\Entity\Post;
use App\Domain\Exception\PostNotFoundException;
use App\Infrastructure\Persistence\Memory\InMemoryPostRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InMemoryPostRepositoryTest extends TestCase
{
    public function testSavePost()
    {
        $repository = new InMemoryPostRepository();
        $this->assertCount(0, $repository->getAll());

        $uuid = Uuid::uuid4();
        $post1 = new Post((string) $uuid, 'Title', 'content');
        $repository->save($post1);
        $this->assertCount(1, $repository->getAll());

        sleep(1);
        $uuid = Uuid::uuid4();
        $post2 = new Post((string) $uuid, 'Title 2', 'content 2');
        $repository->save($post2);
        $this->assertCount(2, $repository->getAll());

        $firstPost = $repository->getById($post1->getId());
        $this->assertEquals($firstPost->getId(), $post1->getId());
        $this->assertEquals($firstPost->getTitle(), $post1->getTitle());
        $this->assertEquals($firstPost->getContent(), $post1->getContent());

        $lastPost = $repository->getById($post2->getId());
        $this->assertEquals($lastPost->getId(), $post2->getId());
        $this->assertEquals($lastPost->getTitle(), $post2->getTitle());
        $this->assertEquals($lastPost->getContent(), $post2->getContent());
    }

    public function testUpdatePost()
    {
        $repository = new InMemoryPostRepository();

        $uuid = Uuid::uuid4();
        $post1 = new Post((string) $uuid, 'Title', 'content');
        $repository->save($post1);
        $this->assertCount(1, $repository->getAll());

        $uuid = Uuid::uuid4();
        $post2 = new Post((string) $uuid, 'Title 2', 'content 2');
        $repository->save($post2);
        $this->assertCount(2, $repository->getAll());

        $firstPost = $repository->getById($post1->getId());
        $this->assertEquals($post1->getId(), $firstPost->getId());
        $this->assertEquals($post1->getTitle(), $firstPost->getTitle());

        $post3 = new Post($firstPost->getId(), 'Title updated', 'content updated');
        $repository->update($post3);
        $this->assertCount(2, $repository->getAll());

        $firstPost = $repository->getById($post1->getId());
        $this->assertEquals($firstPost->getId(), $post3->getId());
        $this->assertEquals($firstPost->getTitle(), $post3->getTitle());
        $this->assertEquals($firstPost->getContent(), $post3->getContent());
    }

    public function testDeletePost()
    {
        $repository = new InMemoryPostRepository();

        $uuid = Uuid::uuid4();
        $post1 = new Post((string) $uuid, 'Title', 'content');
        $repository->save($post1);

        $uuid = Uuid::uuid4();
        $post2 = new Post((string) $uuid, 'Title 2', 'content 2');
        $repository->save($post2);

        $this->assertCount(2, $repository->getAll());

        $repository->delete($post1);
        $this->assertCount(1, $repository->getAll());
    }

    public function testGetByIdThrowNotFound()
    {
        $this->expectException(PostNotFoundException::class);

        $repository = new InMemoryPostRepository();

        $uuid = Uuid::uuid4();
        $repository->getById((string) $uuid);
    }

    public function testGetAllPost()
    {
        $repository = new InMemoryPostRepository();
        $this->assertCount(0, $repository->getAll());

        $uuid = Uuid::uuid4();
        $post1 = new Post((string) $uuid, 'Title', 'content');
        $repository->save($post1);
        $this->assertCount(1, $repository->getAll());

        $uuid = Uuid::uuid4();
        $post2 = new Post((string) $uuid, 'Title 2', 'content 2');
        $repository->save($post2);
        $this->assertCount(2, $repository->getAll());

        $all = $repository->getAll();
        $this->assertCount(2, $all);
        $this->assertInstanceOf(Post::class, $all[0]);
        $this->assertInstanceOf(Post::class, $all[1]);
    }
}