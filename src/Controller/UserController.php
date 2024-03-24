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
    #[Route('/analystic', name: 'analystic')]
    public function getAnalystic() : Response{
    return $this->render('admin/component/analystics.html.twig');
    }
    #[Route('/users', name: 'users')]
    public function users() : Response{
    return $this->render('admin/component/users.html.twig');
    }
    #[Route('/programs', name: 'programs')]
    public function programs() : Response{
    return $this->render('admin/component/programs.html.twig');
    }
    #[Route('/gyms', name: 'gyms')]
    public function gyms() : Response{
    return $this->render('admin/component/gyms.html.twig');
    }
    #[Route('/products', name: 'produts')]
    public function products() : Response{
    return $this->render('admin/component/products.html.twig');
    }
    #[Route('/reclamation', name: 'reclamation')]
    public function reclamation() : Response{
    return $this->render('admin/component/reclamation.html.twig');
    }

}
