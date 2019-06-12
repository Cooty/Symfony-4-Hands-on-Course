<?php


namespace App\Controller\MicroPost;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security as SecurityBundle;

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
        RouterInterface $router
    )
    {
        $this->microPostRepository = $microPostRepository;
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * @Route("/micro-post/add", name="micro_post_add")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param SecurityBundle $security
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add(Request $request, SecurityBundle $security): Response
    {
        $microPost = new MicroPost();

        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $microPost->setUser($security->getUser());
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