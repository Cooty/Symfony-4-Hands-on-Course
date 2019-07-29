<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

// Subscribers can listen to as many events as we wish
// but they should be kept small and specific
class UserSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
//        $event->getRegisteredUser();
    }

}