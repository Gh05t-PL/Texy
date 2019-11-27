<?php

namespace App\Repository;

use App\Entity\TextEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TextEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextEntity[]    findAll()
 * @method TextEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextEntity::class);
    }

    // /**
    //  * @return TextEntity[] Returns an array of TextEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TextEntity
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
