<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Entity;

use App\Domain\Entity\Post;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PostTest extends TestCase
{
    public function testShouldConstruct()
    {
        $uuid = Uuid::uuid4();
        $post = new Post((string) $uuid, "Title test", "content test");

        $this->assertEquals($uuid, $post->getId());
        $this->assertEquals("Title test", $post->getTitle());
        $this->assertEquals("content test", $post->getContent());
    }

    public function testSettersGetters()
    {
        $uuid = Uuid::uuid4();
        $post = new Post((string) $uuid, "Title test", "content test");

        $uuid2 = Uuid::uuid4();
        $post->setId((string) $uuid2);

        $post->setTitle("Title test new");
        $post->setContent("content test new");

        $this->assertEquals($uuid2, $post->getId());
        $this->assertEquals("Title test new", $post->getTitle());
        $this->assertEquals("content test new", $post->getContent());
    }

    public function testGetCreatedAt()
    {
        $uuid = Uuid::uuid4();
        $post = new Post((string) $uuid, "Title test", "content test");

        $this->assertInstanceOf(DateTimeInterface::class, $post->getCreatedAt());
        $this->assertEquals($post->getUpdatedAt(), $post->getCreatedAt());
    }

    public function testJsonSerialize()
    {
        $uuid = Uuid::uuid4();
        $post = new Post((string) $uuid, "Title test", "content test");

        $expectedPayload = json_encode([
            'id' => $uuid,
            'title' => "Title test",
            'content' => "content test",
            'createdAt' => $post->getCreatedAt(),
            'updatedAt' => $post->getUpdatedAt(),
        ]);

        $this->assertEquals($expectedPayload, json_encode($post));
    }

    public function testShouldTouch()
    {
        $uuid = Uuid::uuid4();
        $post = new Post((string) $uuid, "Title test", "content test");

        $updatedAt1 = $post->getUpdatedAt();
        sleep(1);
        $post->touch();
        $updatedAt2 = $post->getUpdatedAt();

        $this->assertGreaterThan($updatedAt1, $updatedAt2);
        $this->assertNotEquals($updatedAt1, $updatedAt2);
    }
}