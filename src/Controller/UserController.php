<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
  
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/getAll', name: 'app_listDB')]
    public function getAll(UserRepository $repo) : Response{
        $list = $repo->findAll();
        return $this->render('author/listDB.html.twig',[
          'users'=>$list
        ]);
    }

}
