<?php

namespace App\Event;

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

    public function __construct(Mailer $mailer, LoggerInterface $logger)
    {

        $this->mailer = $mailer;
        $this->logger = $logger;
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
            $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage().' '.$e->getTraceAsString());
        }
    }

}