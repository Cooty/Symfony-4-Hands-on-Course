<?php

namespace App\Event;

use App\Entity\UserPreferences;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;
use App\Mailer\Mailer;

// Subscribers can listen to as many events as we wish
// but they should be kept small and specific
class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(
        Mailer $mailer,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        string $defaultLocale
    ) {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }


    /**
     * @param UserRegisterEvent $event
     */
    public function onUserRegister(UserRegisterEvent $event)
    {
        try {
            $preferences = new UserPreferences();
            $preferences->setLocale($this->defaultLocale);

            $user = $event->getRegisteredUser();
            $user->setPreferences($preferences);

            $this->entityManager->flush();

            $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage().' '.$e->getTraceAsString());
        }
    }

}