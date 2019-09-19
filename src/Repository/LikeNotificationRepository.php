<?php

namespace App\Repository;

use App\Entity\LikeNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LikeNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeNotification[]    findAll()
 * @method LikeNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeNotificationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LikeNotification::class);
    }
}
