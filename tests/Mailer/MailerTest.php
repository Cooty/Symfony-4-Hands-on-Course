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
        $email = 'john.doe@example.com';

        $user = new User();
        $user->setEmail($email);
        $user->setFullName('John Doe');
        $user->setUsername('john_doe');

        $swiftMailerMock = $this->createMock(Swift_Mailer::class);

        $swiftMailerMock
            ->expects($this->once())
            ->method('send')
            ->with($this->callback(function($subject) use ($email) {
                $messageStr = (string)$subject;

                dump($messageStr);

                return strpos($messageStr, 'From: test@example.com') !== false &&
                    strpos($messageStr, 'Content-Type: text/html; charset=utf-8') !== false &&
                    strpos($messageStr, 'Content-Type: text/html; charset=utf-8') !== false &&
                    strpos($messageStr, "To: $email") !== false &&
                    strpos($messageStr, "This is a message body") !== false;
            }));

        $twigMock = $this->createMock(Environment::class);
        $twigMock->expects($this->once())->method('render')
            ->with('emails/registration.html.twig', ['user' => $user])
            ->willReturn('This is a message body');

        $mailer = new Mailer($swiftMailerMock, $twigMock, 'test@example.com');

        try {
            $mailer->sendConfirmationEmail($user);
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
}