<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request ;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Cookie ;
class AuthentificationController extends AbstractController
{

    #[Route('/register', name: 'register')]
    public function register(Request $request, ManagerRegistry $managerRegistry): Response
    {

        $entityManager = $managerRegistry->getManager();
        $username = $request->request->get('name');
        $lastName = $request->request->get('lastName');
        $email = $request->request->get('email');
        $age = $request->request->get('age');
        $phoneNumber = $request->request->get('phoneNumber');
        $password = $request->request->get('password');
        $user = new User();
        $user->setNom($username);
        $user->setPrenom($lastName);
        $user->setEmail($email);
        $user->setAge($age);
        $user->setPhoneNumber($phoneNumber);
        $user->setPassword($password);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('login');
    }

    
    #[Route('/registerAccount', name:'registerAccount')]
    public function RegisterAccount(): Response
    {
        return $this->render('authentification/register.html.twig');
    }


    #[Route('/login', name:'login')]
    public function index(Request $request): Response
    {
       
        if ($this->isUserAuthenticated($request)) {
            return $this->redirectToRoute('app_user'); 
        }
        
        return $this->render('authentification/login.html.twig');
    }
    
    #[Route('/authenticate', name: 'authenticate', methods: ['POST'])]
    public function authenticate(Request $request): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if ($this->isValidCredentials($email, $password)) {
            $token = md5(uniqid(rand(), true));
            $response = new Response();
            $response->headers->setCookie(new Cookie('auth_token', $token));
            $response->send();
            $this->storeTokenInSession($request, $token);
            return $this->redirectToRoute('dashboard');
        } else {
            return $this->redirectToRoute('login', ['error' => 'Invalid credentials']);
        }
    }
    
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(Request $request): Response
    {
       
        if (!$this->isUserAuthenticated($request)) {
            return $this->redirectToRoute('login'); 
        }
        
        return $this->render('admin/index.html.twig');
    }
    

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();
        $response = new Response();
        $response->headers->clearCookie('auth_token');
        $response->send();   
        return $this->redirectToRoute('login');
    }


    private function isUserAuthenticated(Request $request): bool
    {
        $session = $request->getSession();
        $token = $session->get('auth_token');
        $cookieToken = $request->cookies->get('auth_token');
        
        return $token && $token === $cookieToken;
    }


    private function storeTokenInSession(Request $request, string $token): void
    {
        $session = $request->getSession();
        $session->set('auth_token', $token);
    }

    private function isValidCredentials(Request $request): bool
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $validUser = $userRepository->findOneBy(['email' => $email, 'password' => $password]);
        return $validUser !== null;
    }

}
