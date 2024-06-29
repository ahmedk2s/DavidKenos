<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // Compte tous les utilisateurs filtrer par chocolaterie et rôle
    public function countAllRegisteredUsers($chocolateShopId = null, $role = null): int
    {
        $queryBuilder = $this->createQueryBuilder('u')
                             ->select('count(u.id)');
                             
        if ($chocolateShopId !== null) {
            $queryBuilder->where('u.chocolateShop = :chocolateShopId')
                         ->setParameter('chocolateShopId', $chocolateShopId);
        }
        
        if ($role !== null) {
            $queryBuilder->andWhere('u.roles LIKE :role')
                         ->setParameter('role', '%' . $role . '%');
        }

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    // Trouve les utilisateurs par rôle
    public function findByRole(string $role)
    {
        return $this->createQueryBuilder('u')
                    ->where('u.roles LIKE :role')
                    ->setParameter('role', '%' . $role . '%')
                    ->getQuery()
                    ->getResult();
    }

    // Trouve les employés par chocolaterie, excluant les super administrateurs
    public function findEmployeesByChocolateShop($chocolateShopId)
    {
        return $this->createQueryBuilder('u')
                    ->where('u.chocolateShop = :chocolateShop')
                    ->andWhere('u.roles NOT LIKE :roles')
                    ->setParameter('chocolateShop', $chocolateShopId)
                    ->setParameter('roles', '%ROLE_SUPER_ADMIN%')
                    ->getQuery()
                    ->getResult();
    }

    // Compte les utilisateurs par chocolaterie
    public function countUsersByChocolateShop($chocolateShopId): int
    {
        return $this->createQueryBuilder('u')
                    ->select('count(u.id)')
                    ->where('u.chocolateShop = :chocolateShop')
                    ->setParameter('chocolateShop', $chocolateShopId)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function findUsersByRoleAndChocolateShop($role, $chocolateShop, $excludeRole = false)
    {
    $queryBuilder = $this->createQueryBuilder('u')
                        ->where('u.chocolateShop = :chocolateShop')
                        ->setParameter('chocolateShop', $chocolateShop);
                        
    if ($excludeRole) {
        $queryBuilder->andWhere('u.roles NOT LIKE :role');
    } else {
        $queryBuilder->andWhere('u.roles LIKE :role');
    }
    
    $queryBuilder->setParameter('role', '%' . $role . '%');
    
    return $queryBuilder->getQuery()->getResult();
    }

    public function findAllExceptLoggedInUser(?User $loggedInUser): array
    {
        $queryBuilder = $this->createQueryBuilder('u');

        if ($loggedInUser !== null) {
            $queryBuilder->andWhere('u.id != :loggedInUserId')
            ->setParameter('loggedInUserId', $loggedInUser->getId());
        }

        return $queryBuilder->getQuery()->getResult();
    }

}


//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

