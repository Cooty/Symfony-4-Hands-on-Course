<?php


namespace App\Controller;

use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MicroPostController
 * @package App\Controller
 * @Route("/micro-post")
 */
class MicroPostController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;

    /**
     * MicroPostController constructor.
     * @param \Twig_Environment $twig
     * @param MicroPostRepository $microPostRepository
     */
    public function __construct(\Twig_Environment $twig, MicroPostRepository $microPostRepository)
    {
        $this->microPostRepository = $microPostRepository;
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index(): Response
    {
        $html = $this->twig->render('micro-post/index.html.twig', [
            'posts' => $this->microPostRepository->findAll()
        ]);

        return new Response($html);
    }
}