<?php

declare(strict_types=1);

namespace App\Domain;

class Token
{
    /**
     * @var array
     */
    public array $decoded;

    /**
     * @param array $decoded
     */
    public function __construct(array $decoded)
    {
        $this->populate($decoded);
    }

    /**
     * @param array $decoded
     */
    public function populate(array $decoded): void
    {
        $this->decoded = $decoded;
    }

    /**
     * @param array $scope
     * @return bool
     */
    public function hasScope(array $scope): bool
    {
        return !!count(array_intersect($scope, $this->decoded["scope"]));
    }
}