<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Actions\Post;

use App\Infrastructure\Persistence\Memory\InMemoryPostRepository;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testAction()
    {
        $postRepository = new InMemoryPostRepository();


    }
}