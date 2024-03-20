<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthentificationController extends AbstractController
{

    #[Route ('/register' , name :'register')]
    public function register() : Response {
        return $this->render('authentification/register.html.twig');
    }

    #[Route('/login', name:'login')]
    public function index(): Response
    {
        return $this->render('authentification/login.html.twig');
    }
}
