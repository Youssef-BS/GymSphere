<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class UserController extends AbstractController
{
  
    #[Route('/getAll/{role?}/{search?}', name: 'app_listDB')]
    public function getAll(UserRepository $repo, ?string $role = null, ?string $search = null): Response
    {
        if ($role === 'admin') {
            $usersQuery = $repo->findBy(['isAdmin' => true]);
        } elseif ($role === 'coach') {
            $usersQuery = $repo->findBy(['isCoach' => true]);
        } else {
            $usersQuery = $repo->findAll();
        }
        if ($search) {
            $usersQuery = $repo->createQueryBuilder('u')
                ->where('u.idUser = :searchTerm')
                ->setParameter('searchTerm', $search)
                ->getQuery()
                ->getResult();
        }
    
        return $this->render('admin/component/users.html.twig', [
            'users' => $usersQuery
        ]);
    }
    

    #[Route('/userUpdate/{id}', name: 'userUpdate', methods: ['POST'])]
    public function updateUser($id, UserRepository $repo, Request $request): Response {
        $user = $repo->findOneBy(['idUser' => $id]);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $user->setPrenom($request->request->get('prenom'));
        $user->setAge($request->request->get('age'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($request->request->get('password'));
        $user->setPhoneNumber($request->request->get('phoneNumber'));
        $role = $request->request->get('role');
        if ($role === 'admin') {
            $user->setIsAdmin(true);
            $user->setIsCoach(false);
        } elseif ($role === 'coach') {
            $user->setIsAdmin(false);
            $user->setIsCoach(true);
        } else {
            $user->setIsAdmin(false);
            $user->setIsCoach(false);
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('userDetail', ['id' => $user->getIdUser()]);
    }


    #[Route('/deleteUser/{id}', name: 'deleteUser', methods: ['GET' , 'POST'])]
    public function deleteUser($id, UserRepository $repo): Response {
        $user = $repo->findOneBy(['idUser' => $id]);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_listDB');
    }

    #[Route('/addUserPage', name: 'addUserPage')]
    public function addUserPage(): Response
    {
    
        return $this->render('admin/component/addUser.html.twig');
    }

    #[Route('/addUser', name: 'addUser', methods: ['POST'])]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $username = $request->request->get('nom');
        $lastName = $request->request->get('prenom');
        $email = $request->request->get('email');
        $age = $request->request->get('age');
        $phoneNumber = $request->request->get('phoneNumber');
        $password = $request->request->get('password');
        $role = $request->request->get('role');

        $user = new User();
        $user->setNom($username);
        $user->setPrenom($lastName);
        $user->setEmail($email);
        $user->setAge($age);
        $user->setPhoneNumber($phoneNumber);
        $user->setPassword($password);
        $user->setIsAdmin($role === 'admin'); 
        $user->setIsCoach($role === 'coach'); 

        // Image upload handling
        $imageFile = $request->files->get('image');
        if ($imageFile instanceof UploadedFile) {
            $fileName = md5(uniqid()).'.'.$imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/images/',
                    $fileName
                );
            
            } catch (FileException $e) {
            
            }
            $user->setPhotoProfile($fileName);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_listDB');
    }



#[Route('/analystic', name: 'analystic')]
public function getAnalystic(UserRepository $userRepository): Response
{

    $normalUserCount = $userRepository->count(['isCoach' => 0, 'isAdmin' => 0]);
    $coachCount = $userRepository->count(['isCoach' => 1, 'isAdmin' => 0]);
    $adminCount = $userRepository->count(['isAdmin' => 1]);
    return $this->render('admin/component/analystics.html.twig', [
        'normalUserCount' => $normalUserCount,
        'coachCount' => $coachCount,
        'adminCount' => $adminCount,
    ]);
}
    #[Route('/users', name: 'users')]
    public function users(UserRepository $repo) : Response{
        $list = $repo->findAll();
        return $this->render('admin/component/users.html.twig', [
            'users' => $list
        ]);
    }

    #[Route('/programs', name: 'programs')]
    public function programs() : Response{
    return $this->render('admin/component/programs.html.twig');
    }
    #[Route('/gyms', name: 'gyms')]
    public function gyms() : Response{
    return $this->render('admin/component/gyms.html.twig');
    }
    #[Route('/products', name: 'products')]
    public function products() : Response{
    return $this->render('admin/component/products.html.twig');
    }
    #[Route('/reclamation', name: 'reclamation')]
    public function reclamation() : Response{
    return $this->render('admin/component/reclamation.html.twig');
    }

    #[Route('/userDetail/{id}', name: 'userDetail' , methods: ['GET'])]
    public function userDetails($id, UserRepository $repo): Response {
        $user = $repo->findOneBy(["idUser" => $id]);
        return $this->render('admin/component/userDetails.html.twig', [
            'user' => $user,
        ]);
    }




    #[Route('/productDetails', name: 'productDetails')]
    public function productDetails() : Response{
    return $this->render('admin/component/produitDetails.html.twig');
    }
    #[Route('/gymDetails', name: 'gymDetails')]
    public function gymDetails() : Response{
    return $this->render('admin/component/gymsDetails.html.twig');
    }
    #[Route('/reclamationDetails', name: 'reclamationDetails')]
    public function reclamationDetails() : Response{
    return $this->render('admin/component/reclamationDetails.html.twig');
    }
    #[Route('/programsDetails', name: 'programsDetails')]
    public function programsDetails() : Response{
    return $this->render('admin/component/programsDetails.html.twig');
    }

}