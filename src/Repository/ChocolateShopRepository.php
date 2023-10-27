<?php

namespace App\Repository;

use App\Entity\ChocolateShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChocolateShop>
 *
 * @method ChocolateShop|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChocolateShop|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChocolateShop[]    findAll()
 * @method ChocolateShop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChocolateShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChocolateShop::class);
    }

    public function countAllChocolate(): int
    {
        return $this->createQueryBuilder('cs')
            ->select('count(cs.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return ChocolateShop[] Returns an array of ChocolateShop objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ChocolateShop
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
