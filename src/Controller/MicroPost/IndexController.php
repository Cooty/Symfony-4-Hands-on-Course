<?php


namespace App\Controller\MicroPost;

use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class MicroPostController
 * @package App\Controller
 */
class IndexController
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
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        \Twig_Environment $twig,
        MicroPostRepository $microPostRepository,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        RouterInterface $router)
    {
        $this->microPostRepository = $microPostRepository;
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * @Route("/micro-post", name="micro_post_index")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): Response
    {
        $html = $this->twig->render('micro-post/index.html.twig', [
//            Just get all of them, but this method can't do custom sorting it only sorts by ID
//            'posts' => $this->microPostRepository->findAll()
            'posts' => $this->microPostRepository->findBy([], ['time' => 'DESC'])
        ]);

        return new Response($html);
    }
}