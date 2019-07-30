<?php

namespace App\Controller\MicroPost;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Psr\Log\LoggerInterface;

class SecurityController
{
    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(\Twig\Environment $twig, LoggerInterface $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws \Exception
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return new Response($this->twig->render(
            'security/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ]
        ));
    }

    /**
     * @Route("/confirm/{token}", name="security_confirm")
     * @param string $token
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function confirm(
        string $token,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
        $user = $userRepository->findOneBy([
            'confirmationToken' => $token
        ]);

        if($user !== null) {
            $user->setEnabled(true);
            $user->setConfirmationToken('');

            try {
                $entityManager->flush();
            } catch(\Exception $e) {
                $this->logger->error($e->getMessage().' '.$e->getTraceAsString());
            }
        }

        return new Response($this->twig->render('security/confirmation.html.twig',
            ['user'=> $user]));
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        // empty function - Symfony will do all the work just need a dummy action for the route,
        // we've set in security.yml as the "logout -> path"
    }

}