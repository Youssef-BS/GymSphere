<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Repondre;
use App\Entity\Reclamation;


/**
 * @extends ServiceEntityRepository<Repondre>
 *
 * @method Repondre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repondre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repondre[]    findAll()
 * @method Repondre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class RepondreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repondre::class);
    }


//    /**
//     * @return Repondre[] Returns an array of Repondre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//  

}