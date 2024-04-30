<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request ;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Firebase\JWT\JWT;
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

    #[Route('/login', name: 'login')]
    public function index(Request $request): Response
    {
        if ($this->isUserAuthenticated($request)) {
            return $this->redirectToRoute('dashboard');
        }
        
        return $this->render('authentification/login.html.twig');
    }

    #[Route('/authenticate', name: 'authenticate', methods: ['POST'])]
    public function authenticate(Request $request): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        if ($this->isValidCredentials($email, $password)) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
            $payload = [
                'idUser' => $user->getIdUser(),
                'isAdmin' => $user->getIsAdmin(),
                'isCoach' => $user->getIsCoach(),
                'exp' => time() + (5 * 24 * 60 * 60), 
            ];
            $token = JWT::encode($payload, 'YSF', 'HS256');
            $this->storeTokenInSession($request, $token);
            return $this->redirectToRoute('dashboard');
        } else {
            return $this->redirectToRoute('login', ['error' => 'Password or Email is incorrect !']);
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
    
    #[Route('/client', name: 'client')]
    public function client(Request $request): Response
    {
        // if (!$this->isUserAuthenticated($request)) {
        //     return $this->redirectToRoute('login');
        // }
        
        return $this->render('client/index.html.twig');
    }


    #[Route('/logout', name: 'logout')]

    public function logout(Request $request): Response
    {
        $request->getSession()->remove('auth_token');
        return $this->redirectToRoute('login');
    }

    private function isUserAuthenticated(Request $request): bool
    {
        return $request->getSession()->has('auth_token');
    }

    
    private function isAdminAuthenticated(Request $request): bool 
    {
        if ($request->getSession()->has('auth_token')) {
            $token = $request->getSession()->get('auth_token');
            try {
                $decodedToken = JWT::decode($token, 'YSF', ['HS256']);
                return isset($decodedToken->isAdmin) && $decodedToken->isAdmin === true;
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    private function storeTokenInSession(Request $request, string $token): void
    {
        $request->getSession()->set('auth_token', $token);
    }

    private function isValidCredentials(string $email, string $password): bool
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $validUser = $userRepository->findOneBy(['email' => $email, 'password' => $password]);
        return $validUser !== null;
    }
    
}
