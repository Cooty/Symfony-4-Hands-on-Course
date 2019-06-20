<?php


namespace App\Controller\MicroPost;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 * @package App\Controller\MicroPost
 */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}",  name="micro_post_following_follow")
     * @param User $userToFollow
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function follow(User $userToFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        /* check on a Controller level that a user can not follow himself (not displaying the button is not enough) */
        if($currentUser->getId() !== $userToFollow->getId()) {
            /* Automatically prepares the insert statement on the table we've joined to represent the relationship  */
            $currentUser->getFollowing()->add($userToFollow);

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('micro_post_user', ['username' => $userToFollow->getUsername()]);
    }

    /**
     * @Route("/unfollow/{id}",  name="micro_post_following_unfollow")
     * @param User $userToUnfollow
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unfollow(User $userToUnfollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        /* this check is a it overkill cause we already prevented the user from following himself in the follow() action */
        if($currentUser->getId() !== $userToUnfollow->getId()) {
            /* creates a DELETE SQL query on the following table in ID of the current */
            $currentUser->getFollowing()->removeElement($userToUnfollow);

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('micro_post_user', ['username' => $userToUnfollow->getUsername()]);
    }
}