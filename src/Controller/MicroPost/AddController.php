<?php


namespace App\Controller\MicroPost;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class MicroPostController
 * @package App\Controller
 */
class AddController
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
     * @Route("/micro-post/add", name="micro_post_add")
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function add(Request $request): Response
    {
        $microPost = new MicroPost();
        $microPost->setTime(new \DateTime());

        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();
            return new RedirectResponse($this->router->generate('micro_post_index'));
        }

        $html = $this->twig->render(
            'micro-post/add.html.twig',
            ['form' => $form->createView()]
        );

        return new Response($html);
    }
}