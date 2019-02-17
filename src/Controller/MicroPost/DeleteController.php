<?php


namespace App\Controller\MicroPost;

use App\Entity\MicroPost;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class DeleteController
{
    /**
     * @var Route
     */
    private $router;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        FlashBagInterface $flashBag
    ) {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("micro-post/delete/{id}", name="micro_post_delete")
     * @param MicroPost $microPost
     * @return RedirectResponse
     */
    public function delete(MicroPost $microPost)
    {
        $id = $microPost->getId();
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();

        $this->flashBag->set('notice', 'The post '.$id.' was deleted');

        return new RedirectResponse(
            $this->router->generate('micro_post_index')
        );
    }

}