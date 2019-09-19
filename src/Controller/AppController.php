<?php
/**
 * Created by PhpStorm.
 * User: tamas
 * Date: 17/01/19
 * Time: 17:31
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AppController extends AbstractController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig) {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        $html = $this->twig->render(
            'base.html.twig'
        );
        return new Response($html);
    }
}