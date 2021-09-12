<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Persistence\Capsule;

use App\Application\Hydrators\PostHydratorFactory;
use App\Domain\Entity\Post;
use App\Domain\Exception\PostNotFoundException;
use App\Domain\Exception\RepositoryException;
use App\Domain\Repository\PostRepositoryInterface;
use App\Infrastructure\Persistence\Capsule\PostRepository;
use App\Infrastructure\Persistence\Memory\InMemoryPostRepository;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\QueryException;
use Phinx\Console\PhinxApplication;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class PostRepositoryTest extends TestCase
{
    use ProphecyTrait;

    protected ContainerInterface $container;
    protected Manager $capsule;
    protected PostRepositoryInterface $postRepository;

    public function setUp(): void
    {
        // run phinx migrations
        $phinxApp = new PhinxApplication();
        $phinxApp->setAutoExit(false);
        $phinxApp->run(new StringInput("migrate -e testing"), new NullOutput());

        // db
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => '/tmp/testing.sqlite3'
        ]);
        $capsule->setAsGlobal();

        $container = $this->prophesize(ContainerInterface::class);
        $container->has('capsule')->willReturn(true);
        $container->get('capsule')->willReturn($capsule);

        $postHydratorFactory = new PostHydratorFactory();

        $this->postRepository = new PostRepository($container->reveal(), $postHydratorFactory);
    }

    public function tearDown(): void
    {
        unlink("/tmp/testing.sqlite3");
    }

    public function testSavePost()
    {
        $this->assertCount(0, $this->postRepository->getAll());

        $uuid = Uuid::uuid4();
        $post1 = new Post((string) $uuid, 'Title', 'content');
        $this->postRepository->save($post1);
        $this->assertCount(1, $this->postRepository->getAll());

        sleep(1);
        $uuid = Uuid::uuid4();
        $post2 = new Post((string) $uuid, 'Title 2', 'content 2');
        $this->postRepository->save($post2);
        $this->assertCount(2, $this->postRepository->getAll());

        $firstPost = $this->postRepository->getById($post1->getId());
        $this->assertEquals($firstPost->getId(), $post1->getId());
        $this->assertEquals($firstPost->getTitle(), $post1->getTitle());
        $this->assertEquals($firstPost->getContent(), $post1->getContent());

        $lastPost = $this->postRepository->getById($post2->getId());
        $this->assertEquals($lastPost->getId(), $post2->getId());
        $this->assertEquals($lastPost->getTitle(), $post2->getTitle());
        $this->assertEquals($lastPost->getContent(), $post2->getContent());
    }

    public function testSaveShouldThrowException()
    {
        $this->expectException(RepositoryException::class);

        $this->assertCount(0, $this->postRepository->getAll());

        $uuid = Uuid::uuid4();
        $post1 = new Post((string) $uuid, 'Title', 'content');
        $this->postRepository->save($post1);
        sleep(1);
        $post2 = new Post((string) $uuid, 'Title 2', 'content 2');
        $this->postRepository->save($post2);
    }

    public function testUpdatePost()
    {
        $uuid = Uuid::uuid4();
        $post1 = new Post((string) $uuid, 'Title', 'content');
        $this->postRepository->save($post1);
        $this->assertCount(1, $this->postRepository->getAll());

        $uuid = Uuid::uuid4();
        $post2 = new Post((string) $uuid, 'Title 2', 'content 2');
        $this->postRepository->save($post2);
        $this->assertCount(2, $this->postRepository->getAll());

        $firstPost = $this->postRepository->getById($post1->getId());
        $this->assertEquals($post1->getId(), $firstPost->getId());
        $this->assertEquals($post1->getTitle(), $firstPost->getTitle());

        $post3 = new Post($firstPost->getId(), 'Title updated', 'content updated');
        $this->postRepository->update($post3);
        $this->assertCount(2, $this->postRepository->getAll());

        $firstPost = $this->postRepository->getById($post1->getId());
        $this->assertEquals($firstPost->getId(), $post3->getId());
        $this->assertEquals($firstPost->getTitle(), $post3->getTitle());
        $this->assertEquals($firstPost->getContent(), $post3->getContent());
    }

    public function testDeletePost()
    {
        $uuid = Uuid::uuid4();
        $post1 = new Post((string) $uuid, 'Title', 'content');
        $this->postRepository->save($post1);

        $uuid = Uuid::uuid4();
        $post2 = new Post((string) $uuid, 'Title 2', 'content 2');
        $this->postRepository->save($post2);

        $this->assertCount(2, $this->postRepository->getAll());

        $this->postRepository->delete($post1);
        $this->assertCount(1, $this->postRepository->getAll());
    }

    public function testGetByIdThrowNotFound()
    {
        $this->expectException(PostNotFoundException::class);

        $uuid = Uuid::uuid4();
        $this->postRepository->getById((string) $uuid);
    }

    public function testGetAllPost()
    {
        $this->assertCount(0, $this->postRepository->getAll());

        $uuid = Uuid::uuid4();
        $post1 = new Post((string) $uuid, 'Title', 'content');
        $this->postRepository->save($post1);
        $this->assertCount(1, $this->postRepository->getAll());

        $uuid = Uuid::uuid4();
        $post2 = new Post((string) $uuid, 'Title 2', 'content 2');
        $this->postRepository->save($post2);
        $this->assertCount(2, $this->postRepository->getAll());

        $all = $this->postRepository->getAll();
        $this->assertCount(2, $all);
        $this->assertInstanceOf(Post::class, $all[0]);
        $this->assertInstanceOf(Post::class, $all[1]);
    }
}