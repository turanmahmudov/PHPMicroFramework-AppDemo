<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validation;

use App\Domain\Validation\PostValidator;
use App\Domain\Validation\ValidationResult;
use PHPUnit\Framework\TestCase;

class PostValidatorTest extends TestCase
{
    public function testShouldReturnValidationResult()
    {
        $postValidator = new PostValidator();

        $this->assertInstanceOf(ValidationResult::class, $postValidator->validate([]));
    }

    /**
     * @dataProvider validationDataProvider
     * @param array $data
     * @param bool $valid
     */
    public function testValidation(array $data, bool $valid)
    {
        $postValidator = new PostValidator();
        $validation = $postValidator->validate($data);

        $this->assertEquals($valid, $validation->isValid());
    }

    public function validationDataProvider()
    {
        return [
            [
                ['title' => 'test title', 'content' => 'test content'], true
            ],
            [
                ['title' => 123, 'content' => 'test content'], false
            ],
            [
                ['title' => 'test title', 'content' => false], false
            ],
            [
                ['title' => 123, 'content' => 'test content'], false
            ],
            [
                ['content' => 'test content'], false
            ],
            [
                ['title' => 'test title'], false
            ],
        ];
    }
}