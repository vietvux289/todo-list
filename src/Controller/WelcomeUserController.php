<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeUserController extends AbstractController
{
    /**
     * @Route("/welcome/user", name="app_welcome_user")
     */
    public function index(): Response
    {
        return $this->render('welcome_user/index.html.twig', [
            'controller_name' => 'WelcomeUserController',
        ]);
    }
}
