<?php

declare(strict_types=1);

namespace App\Domain\Validation;

interface ValidatorInterface
{
    /**
     * @param array $data
     * @return ValidationResult
     */
    public function validate(array $data): ValidationResult;
}