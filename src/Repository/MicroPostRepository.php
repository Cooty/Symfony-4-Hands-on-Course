<?php

namespace App\Repository;

use App\Entity\MicroPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MicroPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method MicroPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method MicroPost[]    findAll()
 * @method MicroPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

    /**
      * @param Collection $users
      * @return MicroPost[] Returns an array of MicroPost objects
    */
    public function findAllByUsers(Collection $users)
    {
        $queryBuilder = $this->createQueryBuilder('posts');
        // the order of the query builder methods doesn't matter...
        return $queryBuilder->select('posts')
                    ->where('posts.user IN (:following)')
                    ->setParameter('following', $users)
                    ->orderBy('posts.time', 'DESC')
                    ->getQuery() // ...but it has to end with getQuery which return a Query instance...
                    ->getResult(); // and getResult executes it
    }


    /*
    public function findOneBySomeField($value): ?MicroPost
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
