<?php

namespace App\Repository;

use App\Entity\ScoreEquipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScoreEquipe>
 *
 * @method ScoreEquipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScoreEquipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScoreEquipe[]    findAll()
 * @method ScoreEquipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreEquipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScoreEquipe::class);
    }

//    /**
//     * @return ScoreEquipe[] Returns an array of ScoreEquipe objects
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

//    public function findOneBySomeField($value): ?ScoreEquipe
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
