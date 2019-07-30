<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

// Subscribers can listen to as many events as we wish
// but they should be kept small and specific
class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }


    /**
     * @param UserRegisterEvent $event
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onUserRegister(UserRegisterEvent $event)
    {
        $registeredUser = $event->getRegisteredUser();
        $body = $this->twig->render('emails/registration.html.twig',
            ['user'=> $registeredUser]);

        $message = (new \Swift_Message())
            ->setSubject('Welcome to the micropost app!')
            ->setFrom('noreply@micropost.com')
            ->setTo($registeredUser->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }

}