<?php

namespace App\Repository;

use App\Entity\Inter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inter>
 *
 * @method Inter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inter[]    findAll()
 * @method Inter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inter::class);
    }

    public function add(Inter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Inter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return Inter[] Returns an array of Inter objects
    */
   public function findByDate($date): array
   {
       return $this->createQueryBuilder('i')
           ->andWhere('i.date = :val')
           ->setParameter('val', $date)
           ->getQuery()
           ->getResult()
       ;
   }
    /**
    * @return Inter[] Returns an array of Inter objects
    */
    public function findByTechAndDate($tech, $date): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.date = :date AND i.technicien = :tech')
            ->setParameter('tech', $tech)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Inter[] Returns an array of Inter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Inter
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
