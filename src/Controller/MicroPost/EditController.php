<?php

namespace App\Controller\MicroPost;

use App\Entity\MicroPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @var \Twig\Environment
     */
    private $twig;

    public function __construct(
        \Twig\Environment $twig,
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
     * @Security("is_granted('edit', microPost)", message="You don't have permission to edit this post")
     * @param MicroPost $microPost
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(MicroPost $microPost, Request $request): Response
    {
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return new RedirectResponse($this->router->generate('micro_post_post', ['id' => $microPost->getId()]));
        }

        $html = $this->twig->render(
            'micro-post/add.html.twig',
            ['form' => $form->createView()]
        );

        return new Response($html);
    }

}