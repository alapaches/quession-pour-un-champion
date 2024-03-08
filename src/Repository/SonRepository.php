<?php

namespace App\Repository;

use App\Entity\Son;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Son>
 *
 * @method Son|null find($id, $lockMode = null, $lockVersion = null)
 * @method Son|null findOneBy(array $criteria, array $orderBy = null)
 * @method Son[]    findAll()
 * @method Son[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Son::class);
    }

//    /**
//     * @return Son[] Returns an array of Son objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Son
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
