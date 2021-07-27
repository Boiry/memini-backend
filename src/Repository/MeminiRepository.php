<?php

namespace App\Repository;

use App\Entity\Memini;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Memini|null find($id, $lockMode = null, $lockVersion = null)
 * @method Memini|null findOneBy(array $criteria, array $orderBy = null)
 * @method Memini[]    findAll()
 * @method Memini[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeminiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Memini::class);
    }

    public function findForHomepage()
    {
        return $this->createQueryBuilder('m')
            ->where('m.public = true')
            ->andWhere('m.isSent = true')
            ->orderBy('m.sendAt', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllPersonalMeminis($id)
    {
        return $this->createQueryBuilder('m')
            ->where('m.isSent = true')
            ->andWhere('m.user = :id')
            ->setParameter('id', $id)
            ->orderBy('m.sendAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllMeminisToSend()
    {
        $currentDateMin = new \DateTime('midnight');
        $currentDateMin = $currentDateMin->format('Y-m-d H:i:s');
        $currentDateMax = new \DateTime('tomorrow');
        $currentDateMax = $currentDateMax->format('Y-m-d H:i:s');

        return $this->createQueryBuilder('m')
        ->andWhere("m.sendAt > :dateMin")
        ->andWhere("m.sendAt < :dateMax")
        ->setParameters(array('dateMin' => $currentDateMin, 'dateMax' => $currentDateMax))
        ->getQuery()
        ->getResult()
        ;
    }
    
}
