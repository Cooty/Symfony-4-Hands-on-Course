<?php

namespace App\Controller\MicroPost;

use App\Entity\MicroPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MicroPostRepository;
use Symfony\Component\Form\FormFactoryInterface;
use App\Form\MicroPostType;
use Symfony\Component\Routing\RouterInterface;

class EditController
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(
        \Twig_Environment $twig,
        MicroPostRepository $microPostRepository,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        RouterInterface $router
    ) {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @Route("/micro-post/edit/{id}", name="micro_post_edit")
     * @param MicroPost $microPost
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(MicroPost $microPost, Request $request): Response
    {
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
//            $this->entityManager->persist($microPost);
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