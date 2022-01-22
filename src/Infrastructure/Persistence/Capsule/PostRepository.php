<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Capsule;

use App\Application\Hydrators\PostHydratorFactory;
use App\Domain\Entity\Post;
use App\Domain\Exception\PostNotFoundException;
use App\Domain\Exception\RepositoryException;
use App\Domain\Repository\PostRepositoryInterface;
use App\Infrastructure\Persistence\Capsule\Helper\FilterQueryBuilder;
use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\QueryException;
use Laminas\Hydrator\HydratorInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use function Functional\map;

class PostRepository extends AbstractRepository implements PostRepositoryInterface
{
    /**
     * @var HydratorInterface
     */
    protected HydratorInterface $hydrator;

    public function __construct(ContainerInterface $container, PostHydratorFactory $hydratorFactory)
    {
        parent::__construct($container);
        $this->hydrator = $hydratorFactory->create();
    }

    public function save(Post $post): void
    {
        $data = $this->hydrator->extract($post);

        try {
            Capsule::table('posts')->insert($data);
        } catch (QueryException $e) {
            throw new RepositoryException('Post is not saved: ' . $e->getMessage());
        }
    }

    public function update(Post $post): void
    {
        $data = $this->hydrator->extract($post);

        try {
            Capsule::table('posts')->where('id', $post->getId())->update($data);
        } catch (QueryException $e) {
            throw new RepositoryException('Post is not updated: ' . $e->getMessage());
        }
    }

    public function delete(Post $post): void
    {
        try {
            Capsule::table('posts')->delete($post->getId());
        } catch (QueryException $e) {
            throw new RepositoryException('Post is not deleted: ' . $e->getMessage());
        }
    }

    public function getAll(int $page = 1, int $limit = 10, ?string $filter = null): array
    {
        try {
            $offset = ($page - 1) * $limit;

            $filterQueryBuilder = new FilterQueryBuilder();
            $filterQuery = $filterQueryBuilder->buildQuery(Capsule::table('posts'), $filter);
            $posts = $filterQuery->offset($offset)->limit($limit)->get();

            return map($posts, function ($post) {
                return $this->hydrator->hydrate(
                    (array) $post,
                    (new ReflectionClass(Post::class))->newInstanceWithoutConstructor()
                );
            });
        } catch (Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }

    /**
     * @throws ReflectionException
     * @throws PostNotFoundException
     */
    public function getById(string $id): Post
    {
        $post = Capsule::table('posts')->find($id);

        if (!$post) throw new PostNotFoundException();

        return $this->hydrator->hydrate(
            (array) $post,
            (new ReflectionClass(Post::class))->newInstanceWithoutConstructor()
        );
    }
}