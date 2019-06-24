<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $x
     * @return User[] Returns an array of User objects
     */
    public function findAllWithMoreThenXPosts(int $x)
    {
        $q = $this->getFindAllWithMoreThenXPostsQuery($x);

        return $q->getQuery()->getResult();
    }

    /**
     * @param int $x
     * @param User $currentUser
     * @return User[] Returns an array of User objects
     */
    public function findAllWithMoreThenXPostsExceptCurrentUser(int $x, User $currentUser)
    {
        $q = $this->getFindAllWithMoreThenXPostsQuery($x);

        return $q->andHaving('user != :currentUser')
            ->setParameter('currentUser', $currentUser)
            ->getQuery()
            ->getResult();
    }

    private function getFindAllWithMoreThenXPostsQuery(int $x): QueryBuilder
    {
        $qb = $this->createQueryBuilder('user');

        return $qb->select('user')
            ->innerJoin('user.posts','mp')
            ->groupBy('user')
            ->having("count(mp) > $x");
    }

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
