<?php

declare(strict_types=1);

use App\Domain\Repository\PostRepositoryInterface;
use App\Infrastructure\Persistence\Capsule\PostRepository;
use function DI\autowire;

return [
    PostRepositoryInterface::class => autowire(PostRepository::class)
];
