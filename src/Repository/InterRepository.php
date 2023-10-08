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
            ->getResult();
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
            ->getResult();
    }

    /**
     * @return Inter[] Returns an array of Inter objects
     */
    public function findByGroupUser($date_debut, $date_fin): array
    {
        return $this->createQueryBuilder('f')
            ->addSelect('count(f.technicien) as nombre')
            ->addSelect('SUM(f.Salaire) as salaire')
            ->andWhere('f.date BETWEEN :date_debut AND :date_fin')
            ->andWhere('f.presence = true')
            ->setParameter('date_debut', $date_debut)
            ->setParameter('date_fin', $date_fin)
            ->groupBy('f.technicien')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Inter[] Returns an array of Inter objects
     */
    public function findInterByUser($technicien, $date_debut, $date_fin): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.technicien = :val')
            ->andWhere('i.date BETWEEN :date_debut AND :date_fin')
            ->andWhere('i.presence = true')
            ->setParameter('date_debut', $date_debut)
            ->setParameter('date_fin', $date_fin)
            ->setParameter('val', $technicien)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Inter[] Returns an array of Inter objects
     */
    public function findInterBetweenDate($date_debut, $date_fin): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.date BETWEEN :date_debut AND :date_fin')
            ->andWhere('i.presence is null OR i.presence = false')
            ->setParameter('date_debut', $date_debut)
            ->setParameter('date_fin', $date_fin)
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Inter[] Returns an array of Inter objects
     */
    public function countInter($client, $date_debut, $date_fin): array
    {
        return $this->createQueryBuilder('i')
            ->select('count(i.technicien) as nombre')
            ->innerJoin('i.technicien', 'u')
            ->addSelect('u.id, u.nom, u.prenom')
            ->andWhere('i.date BETWEEN :date_debut AND :date_fin')
            ->andWhere('i.presence = true')
            ->andWhere('u.client = :client')
            ->setParameter('date_debut', $date_debut)
            ->setParameter('date_fin', $date_fin)
            ->setParameter('client', $client)
            ->groupBy('i.technicien')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Inter[] Returns an array of Inter objects
     */
    public function countInterAll($date_debut, $date_fin): array
    {
        return $this->createQueryBuilder('i')
            ->select('count(i.technicien) as nombre')
            ->innerJoin('i.technicien', 'u')
            ->addSelect('u.id, u.nom, u.prenom')
            ->andWhere('i.date BETWEEN :date_debut AND :date_fin')
            ->andWhere("u.client != 'AUTRE'")
            ->andWhere('i.presence = true')
            ->setParameter('date_debut', $date_debut)
            ->setParameter('date_fin', $date_fin)
            ->groupBy('i.technicien')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Inter[] Returns an array of Inter objects
     */
    public function findCAperYear( $tech): array
    {
        return $this->createQueryBuilder('i')
            ->addSelect('MONTH(i.date) AS mois, YEAR(i.date) AS annee, SUM(i.Salaire) salaire, count(i.Salaire) as qtte')
            ->andWhere('i.presence = true')
            ->andWhere('i.technicien = :tech')

            // ->innerJoin('i.technicien', 'u')
            // ->addSelect('u.id, u.nom, u.prenom')
            ->setParameter('tech', $tech)
            ->groupBy('annee, mois')
            ->getQuery()
            ->getResult();
    }

        /**
     * @return Inter[] Returns an array of Inter objects
     */
    public function findInterByTechPeriod($tech, $date_debut, $date_fin): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.date BETWEEN :date_debut AND :date_fin')
            ->andWhere('i.technicien = :tech')
            ->setParameter('date_debut', $date_debut)
            ->setParameter('date_fin', $date_fin)
            ->setParameter('tech', $tech)
            ->orderBy('i.date', 'ASC')
            ->getQuery()
            ->getResult();
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
