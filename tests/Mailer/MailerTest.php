<?php

namespace App\Tests\Mailer;

use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;
use \Swift_Mailer;
use Twig\Environment;

class MailerTest extends TestCase
{
    public function testConfirmationEmail()
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setFullName('John Doe');
        $user->setUsername('john_doe');

        $swiftMailerMock = $this->createMock(Swift_Mailer::class);
        $twigMock = $this->createMock(Environment::class);


        $mailer = new Mailer($swiftMailerMock, $twigMock, 'test@example.com');

        try {
            $mailer->sendConfirmationEmail($user);
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
}