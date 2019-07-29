<?php

namespace App\Controller\MicroPost;

use App\Event\UserRegisterEvent;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="user_registration")
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(
        UserPasswordEncoderInterface $passwordEncoder,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // alternate way to get the Entity Manager Interface if we're not injecting it
            // comes from the AbstractController
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();

            // The event-object is used to transmit data to the subscribers
            // in this case the data we want to pass around is the newly created user
            $userRegisterEvent = new UserRegisterEvent($user);

            // this is the point where we can safely say that a new User has been added to the DB
            // so we can inform the system of this by the dispatching the event
            $eventDispatcher->dispatch(UserRegisterEvent::NAME, $userRegisterEvent);

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
