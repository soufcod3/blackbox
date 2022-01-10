<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    // Get the movies related to search query
    /**
     * @return Movie[]
     */
    public function findSearch(SearchData $search): array
    {
        $query = $this
            ->createQueryBuilder('s')
            ->select('c', 's')
            ->join('s.category', 'c');

        if(!empty($search->q)) {
            $query = $query
                ->andWhere('s.title LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if(!empty($search->category)) {
            $query = $query
                ->andWhere('c.id IN (:category)')
                ->setParameter('category', $search->category);
        }

        return $query->getQuery()->getResult();
    }
}
