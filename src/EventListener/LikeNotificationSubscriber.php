<?php

namespace App\EventListener;

use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;

class LikeNotificationSubscriber implements EventSubscriber
{

    public function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    // Event handlers in Doctrine must match the names of the Event they handle otherwise ti won't work
    // OnFlushEventArgs gives the info that help us fetch the Entity Doctrine is operating on
    /**
     * @param OnFlushEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $entityManager = $args->getEntityManager();
        // Unit of Work keeps track all the changes made to the entities
        $unitOfWork = $entityManager->getUnitOfWork();

        // getScheduledCollectionUpdates gets a list of changes to be made
        // to all the Collection (objects that implement Doctrine/Collection interface)
        /**
         * @var PersistentCollection $collectionUpdate
         */
        foreach($unitOfWork->getScheduledCollectionUpdates() as $collectionUpdate) {
            if(!$collectionUpdate->getOwner() instanceof MicroPost) {
               continue;
            }

            if($collectionUpdate->getMapping()['fieldName'] !== 'likedBy') {
                continue;
            }

            $insertDiff = $collectionUpdate->getInsertDiff();

            if(!count($insertDiff)) {
                return;
            }

            /**
             * @var MicroPost
             */
            $microPost = $collectionUpdate->getOwner();
            $likedBy = reset($insertDiff);

            // Don't notify me when I'm liking my own post
            if($likedBy->getId() === $microPost->getUser()->getId()) {
                return;
            }

            $notification = new LikeNotification();
            $notification->setUser($microPost->getUser());
            $notification->setMicroPost($microPost);
            $notification->setLikedBy($likedBy);

            $entityManager->persist($notification);
            $unitOfWork->computeChangeSet(
                $entityManager->getClassMetadata(LikeNotification::class),
                $notification
            );
        }
    }
}