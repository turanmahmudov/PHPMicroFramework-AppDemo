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
use Laminas\Hydrator\HydratorInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;
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

        if (!Capsule::table('posts')->insert($data)) {
            throw new RepositoryException('Post is not saved.');
        }
    }

    public function update(Post $post): void
    {
        $data = $this->hydrator->extract($post);

        if (!Capsule::table('posts')->where('id', $post->getId())->update($data)) {
            throw new RepositoryException('Post is not updated');
        }
    }

    public function delete(Post $post): void
    {
        if (!Capsule::table('posts')->delete($post->getId())) {
            throw new RepositoryException('Post is not deleted');
        }
    }

    public function getAll(int $page, int $limit, ?string $filter): array
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