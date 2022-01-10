<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Series;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Series|null find($id, $lockMode = null, $lockVersion = null)
 * @method Series|null findOneBy(array $criteria, array $orderBy = null)
 * @method Series[]    findAll()
 * @method Series[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Series::class);
    }

    // Get the series related to search query
    /**
     * @return Series[]
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
