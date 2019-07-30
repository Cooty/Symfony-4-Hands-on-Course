<?php

namespace App\Controller\MicroPost;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\MicroPostRepository;

class UserPostsController extends AbstractController
{

    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;

    public function __construct(
        \Twig\Environment $twig,
        MicroPostRepository $microPostRepository
    )
    {
        $this->microPostRepository = $microPostRepository;
        $this->twig = $twig;
    }

    /**
     * @Route("/user/{username}", name="micro_post_user")
     * @param User $userWithPosts
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function userPosts(User $userWithPosts)
    {
//        $posts = $userWithPosts->getPosts();

        $html = $this->twig->render('micro-post/user-posts.html.twig', [
            'posts' => $this->microPostRepository->findBy(
                ['user'=> $userWithPosts],
                ['time' => 'DESC']
            ),
            'user' => $userWithPosts,
            // Lazy loaded internally by Doctrine's generated proxy-class
//            'posts'=> $posts
        ]);

        return new Response($html);
    }

}