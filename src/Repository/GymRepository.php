<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Gym;

class GymRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gym::class);
    }

    // Add your custom repository methods here

    // public function findByAddress($addresse): array
    // {
    //     return $this->createQueryBuilder('g')
    //         ->andWhere('g.adresse LIKE :addresse')
    //         ->setParameter('addresse', '%'.$addresse.'%')
    //         ->getQuery()
    //         ->getResult();
    // }
}