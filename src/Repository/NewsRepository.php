<?php

namespace App\Repository;

use App\Entity\News;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<News>
 *
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function countAllNews(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findLatest(int $limit): array
{
    return $this->createQueryBuilder('n')
        ->orderBy('n.dateCreation', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}
    public function findBySearch(SearchData $searchData)
{
    $data = $this->createQueryBuilder('p');
        // ->addOrderBy('p.uptatedAt', 'DESC');

    if (!empty($searchData->q)) {
        $data = $data
            ->andWhere('p.title LIKE :q')
            ->setParameter('q', "%{$searchData->q}%");
    }

    $news = $data
        ->getQuery()
        ->getResult();

    return $news;
}


//    /**
//     * @return News[] Returns an array of News objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?News
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

