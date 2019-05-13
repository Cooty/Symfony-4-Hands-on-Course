<?php

namespace App\Security;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MicroPostVoter extends Voter
{
    // the actions to vote on
    const EDIT = 'edit';
    const DELETE = 'delete';

    // Determines if this voter even applies to the action and the object that is passed
    // We only want to check if a user has permission to EDIT or DELETE a MicroPost...
    // for other things we would create another voter class
    // this will always be called before voteOnAttribute so if it returns FALSE then the second
    // method is not even called
    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if(!$subject instanceof MicroPost) {
            return false;
        }

        return true;
    }

    // this method checks the actual permissions
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $authenticatedUser = $token->getUser();

        if(!$authenticatedUser instanceof User) {
            return false;
        }

        /**
         * @var MicroPost $microPost
         */
        $microPost = $subject;

        // now let's check if the current user is the same user as the user of the micropost
        // (we know that from the relation between them)
        return $microPost->getUser()->getId() === $authenticatedUser->getId();
    }
}