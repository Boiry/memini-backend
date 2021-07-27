<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findByMemini($id)
    {
        return $this->createQueryBuilder('c')
            ->select('c.id')
            ->addSelect('c.content')
            ->addSelect('c.createdAt')
            ->leftJoin('c.user', 'u')
            ->addSelect('u.name')
            ->addSelect('u.avatar')
            ->where('c.memini = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function delete($id)
    {
        return $this->createQueryBuilder('c')
            ->delete()
            ->where('c.memini = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute()
        ;
    }
}
