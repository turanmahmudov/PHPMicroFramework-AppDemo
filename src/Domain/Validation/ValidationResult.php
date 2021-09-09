<?php

declare(strict_types=1);

namespace App\Domain\Validation;

class ValidationResult
{
    /**
     * @var bool
     */
    protected bool $isValid;

    /**
     * @var array
     */
    protected array $messages;

    /**
     * ValidationResult constructor.
     * @param bool $isValid
     * @param array $messages
     */
    public function __construct(bool $isValid = true, array $messages = [])
    {
        $this->isValid = $isValid;
        $this->messages = $messages;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     */
    public function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param string $message
     */
    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }
}