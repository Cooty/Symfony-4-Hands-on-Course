<?php

namespace App\Controller\MicroPost;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/likes")
 * @package App\Controller\MicroPost
 */
class LikesController extends AbstractController
{
    /**
     * @Route("/like/{id}", name="micro_post_likes_like")
     * @param MicroPost $post
     * @return JsonResponse
     */
    public function like(MicroPost $post): JsonResponse
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();

        if(!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $post->like($currentUser);

        $this->getDoctrine()->getManager()->flush();

        return $this->makeSuccessResponse($post);
    }

    /**
     * @Route("/unlike/{id}", name="micro_post_likes_unlike")
     * @param MicroPost $post
     * @return JsonResponse
     */
    public function unlike(MicroPost $post)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();

        if(!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $post->getLikedBy()->removeElement($currentUser);

        $this->getDoctrine()->getManager()->flush();

        return $this->makeSuccessResponse($post);
    }

    /**
     * @param MicroPost $post
     * @return JsonResponse
     */
    private function makeSuccessResponse(MicroPost $post)
    {
        return new JsonResponse([
            'count'=> $post->getLikedBy()->count()
        ], Response::HTTP_OK);
    }
}