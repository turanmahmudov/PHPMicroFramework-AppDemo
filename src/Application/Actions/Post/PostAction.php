<?php

declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Application\Hydrators\PostHydratorFactory;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Token;
use Framework\Http\Exception\ForbiddenException;
use Laminas\Hydrator\HydratorInterface;
use Psr\Container\ContainerInterface;

class PostAction
{
    protected array $scope = ['post.all'];
    protected string $errorMessage = 'Token is not allowed';

    /**
     * @var Token|mixed
     */
    protected Token $token;

    /**
     * @var PostRepositoryInterface
     */
    protected PostRepositoryInterface $postRepository;

    /**
     * @var HydratorInterface
     */
    protected HydratorInterface $hydrator;

    /**
     * @param ContainerInterface $container
     * @param PostRepositoryInterface $postRepository
     * @param PostHydratorFactory $hydratorFactory
     * @throws ForbiddenException
     */
    public function __construct(
        ContainerInterface $container,
        PostRepositoryInterface $postRepository,
        PostHydratorFactory $hydratorFactory
    ) {
        $this->token = $container->get('token');
        $this->postRepository = $postRepository;
        $this->hydrator = $hydratorFactory->create();

        if (!$this->token->hasScope($this->scope)) {
            throw new ForbiddenException($this->errorMessage);
        }
    }
}