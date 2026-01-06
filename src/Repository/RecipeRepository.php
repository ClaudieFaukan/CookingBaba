<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @return Recipe[] Returns an array of Recipe objects
     */
    public function findByDurationLowerThan(int $duration): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->leftJoin('r.category', 'c')
            ->andWhere('r.duration <= :duration')
            ->setParameter('duration', $duration)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findTotalDuration(): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('SUM(r.duration) as total_duration')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function paginate(int $page = 1, int $limit = 10)
    {
        $query = $this->createQueryBuilder('r')
            ->leftJoin('r.category', 'c')
            ->select('r', 'c')
            ->orderBy('r.id', 'ASC')
            ->getQuery();

        return $this->paginator->paginate(
            $query,
            $page,
            $limit,
            [
                'distinct' => true,
                'allowedSortFields' => ['r.title','c.name','r.duration']
            ]
        );
    }
}
