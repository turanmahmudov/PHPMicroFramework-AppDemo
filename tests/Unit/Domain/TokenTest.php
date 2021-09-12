<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function testShouldConstruct()
    {
        $decoded = [
            'secret' => '123',
            'scope' => ['post.all', 'post.list']
        ];
        $token = new Token($decoded);

        $this->assertEquals($decoded, $token->decoded);
    }

    public function testPopulate()
    {
        $decoded = [
            'secret' => '123',
            'scope' => ['post.all', 'post.list']
        ];
        $token = new Token($decoded);

        $decoded2 = [
            'secret' => '1234',
            'scope' => ['post.all', 'post.read']
        ];
        $token->populate($decoded2);

        $this->assertNotEquals($decoded, $token->decoded);
        $this->assertEquals($decoded2, $token->decoded);
    }

    public function testHasScope()
    {
        $decoded = [
            'secret' => '123',
            'scope' => ['post.all', 'post.list', 'post.create']
        ];
        $token = new Token($decoded);

        $this->assertTrue($token->hasScope(['post.list']));
        $this->assertTrue($token->hasScope(['post.list', 'post.create']));
        $this->assertTrue($token->hasScope(['post.list', 'post.all']));
        $this->assertTrue($token->hasScope(['post.create']));
        $this->assertTrue($token->hasScope(['post.list', 'post.all', 'post.create']));
        $this->assertTrue($token->hasScope(['post.list', 'post.read']));
        $this->assertTrue($token->hasScope(['post.list', 'post.update']));
        $this->assertNotTrue($token->hasScope(['post.read']));
        $this->assertNotTrue($token->hasScope(['post.update']));
    }
}