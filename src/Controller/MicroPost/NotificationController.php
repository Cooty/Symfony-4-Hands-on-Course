<?php

namespace App\Controller\MicroPost;

use App\Repository\NotificationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotificationController
 * @package App\Controller\MicroPost
 * @Security("is_granted('ROLE_USER')")
 * @Route("/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @Route("/unread-count", name="notification_unread")
     */
    public function unreadCount()
    {
        try {
            return $this->json([
                'count'=> $this->notificationRepository->findUnseenByUser($this->getUser())
            ]);
        } catch (\Exception $e) {
            return $this->json(
                ['error' => $e->getTraceAsString()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @Route("/all", name="notification_all")
     */
    public function notifications()
    {
        return $this->render('notifications/index.html.twig', [
            'notifications'=> $this->notificationRepository->findBy([
                'seen'=> false,
                'user'=> $this->getUser()
            ])
        ]);
    }

}