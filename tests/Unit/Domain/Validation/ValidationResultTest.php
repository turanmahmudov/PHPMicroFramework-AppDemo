<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validation;

use App\Domain\Validation\ValidationResult;
use PHPUnit\Framework\TestCase;

class ValidationResultTest extends TestCase
{
    public function testValidationResultShouldConstruct()
    {
        $validationResult = new ValidationResult(false, [
            "Field is required: title"
        ]);

        $this->assertEquals(["Field is required: title"], $validationResult->getMessages());
        $this->assertEquals(false, $validationResult->isValid());
    }

    public function testValidationResultGettersSetters()
    {
        $validationResult = new ValidationResult(false, [
            "Field is required: title"
        ]);

        $validationResult->setIsValid(true);
        $validationResult->addMessage("Field is required: content");

        $this->assertTrue($validationResult->isValid());
        $this->assertCount(2, $validationResult->getMessages());
    }
}