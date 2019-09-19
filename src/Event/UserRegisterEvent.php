<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserRegisterEvent extends Event
{
    // This is what identifies the Event throughout Symfony. Must be unique!
    const NAME = 'user.register';

    /**
     * @var User
     */
    private $registeredUser;

    public function __construct(User $registeredUser)
    {
        $this->registeredUser = $registeredUser;
    }

    /**
     * @return User
     */
    public function getRegisteredUser(): User
    {
        return $this->registeredUser;
    }

}