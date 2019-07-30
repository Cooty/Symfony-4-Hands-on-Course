<?php

namespace App\Controller\MicroPost;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController
{
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;

    /**
     * @var \Twig\Environment
     */
    private $twig;

    public function __construct(MicroPostRepository $microPostRepository, \Twig\Environment $twig)
    {
        $this->microPostRepository = $microPostRepository;
        $this->twig = $twig;
    }

    /**
     * @Route("micro-post/post/{id}", name="micro_post_post")
     * @param MicroPost $post
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function post(MicroPost $post)
    {
//        Don't need this because Symfony's param-converter automatically looks the passed variable
//    from the URL to the entity, when type-hinted
//        $post = $this->microPostRepository->find($id);

        return new Response(
            $this->twig->render('micro-post/post.html.twig', ['post' => $post])
        );
    }
}