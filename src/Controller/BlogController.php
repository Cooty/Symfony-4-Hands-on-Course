<?php
/**
 * Created by PhpStorm.
 * User: tamas
 * Date: 17/01/19
 * Time: 17:31
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Greetings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class BlogController
{
    /**
     * @var Greetings
     */
    private $greetings;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        Greetings $greetings,
        \Twig_Environment $twig,
        SessionInterface $session,
        RouterInterface $router
    ) {
        $this->greetings = $greetings;
        $this->twig = $twig;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Route("/blog", name="blog_index")
     */
    public function index(): Response
    {
        $html = $this->twig->render(
            'blog/index.html.twig',
            [
                'posts' => $this->session->get('posts')
            ]
        );
        return new Response($html);
    }

    /**
     * @Route("/blog/post/add", name="blog_post_add")
     * @return Response
     */
    public function addPost(): Response
    {
        $posts = $this->session->get('posts');
        $id = uniqid();

        $posts[$id] = [
            'title' => 'A random title '.rand(1, 500),
            'text' => 'Some random text '.rand(1, 500),
            'date' => new \DateTime()
        ];

        $this->session->set('posts', $posts);

        return new RedirectResponse($this->router->generate('blog_index'));
    }

    /**
     * @Route("/blog/posts/{id}", name="blog_post")
     * @param string $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showPost(string $id): Response
    {
        $posts = $this->session->get('posts');

        if(!$posts || !isset($posts[$id])) {
            throw new NotFoundHttpException("Post id $id not found");
        }

        $html = $this->twig->render(
            'blog/post.html.twig',
            [
                'id' => $id,
                'post' => $posts[$id],
            ]
        );

        return new Response($html);

    }
}