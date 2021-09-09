<?php

declare(strict_types=1);

namespace App\Domain\Validation;

class PostValidator extends AbstractValidator
{
    protected array $fields = [
        'title' => ['required' => true, 'types' => ['string']],
        'content' => ['required' => true, 'types' => ['string']]
    ];
}