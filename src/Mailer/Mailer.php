<?php

namespace App\Mailer;

use App\Entity\User;
use Twig\Environment;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $mailFrom;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $twig
     * @param string $mailFrom
     */
    public function __construct(
        \Swift_Mailer $mailer,
        Environment $twig,
        string $mailFrom
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }

    /**
     * @param User $user
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render('emails/registration.html.twig',
            ['user' => $user]);

        $message = (new \Swift_Message())
            ->setSubject('Welcome to the micropost app!')
            ->setFrom($this->mailFrom)
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }

}