<?php


namespace App\Controller\MicroPost;

use App\Entity\User;
use App\Repository\MicroPostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * @param TokenStorageInterface $tokenStorage
     * @param UserRepository $userRepository
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(TokenStorageInterface $tokenStorage, UserRepository $userRepository): Response
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        $usersToFollow = [];

        if($currentUser instanceof User) {
            $posts = $this->microPostRepository->findAllByUsers($currentUser->getFollowing());

            $usersToFollow = count($posts) === 0 ?
                $userRepository->findAllWithMoreThenXPostsExceptCurrentUser(5, $currentUser) :
                [];
        } else {
            $posts = $this->microPostRepository->findBy(
                [],
                ['time' => 'DESC']
            );
        }
        $html = $this->twig->render('micro-post/index.html.twig', [
//            Just get all of them, but this method can't do custom sorting it only sorts by ID
//            'posts' => $this->microPostRepository->findAll()
            'posts' => $posts,
            'usersToFollow' => $usersToFollow
        ]);

        return new Response($html);
    }
}