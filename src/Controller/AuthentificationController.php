<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request ;
use Doctrine\Persistence\ManagerRegistry;
<<<<<<< HEAD
<<<<<<< HEAD
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
class AuthentificationController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


=======
=======
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
use Symfony\Component\HttpFoundation\JsonResponse;
use Firebase\JWT\JWT;
class AuthentificationController extends AbstractController
{

<<<<<<< HEAD
>>>>>>> 0f9be098c09a370d9b7246eec13ee77203b60875
=======
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
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
<<<<<<< HEAD
<<<<<<< HEAD
        $imageFile = $request->files->get('image');


        $user = new User();
        
        if($imageFile instanceof UploadedFile) {
            $fileName = md5(uniqid()).'.'.$imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/images/', $fileName 
                );
            }catch(FileException $e) {
            
            } ;

            $user->setPhotoProfile($fileName);
        }
=======
        $user = new User();
>>>>>>> 0f9be098c09a370d9b7246eec13ee77203b60875
=======
        $user = new User();
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
        $user->setNom($username);
        $user->setPrenom($lastName);
        $user->setEmail($email);
        $user->setAge($age);
        $user->setPhoneNumber($phoneNumber);
<<<<<<< HEAD
<<<<<<< HEAD
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($encodedPassword);
=======
        $user->setPassword($password);
>>>>>>> 0f9be098c09a370d9b7246eec13ee77203b60875
=======
        $user->setPassword($password);
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
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
<<<<<<< HEAD
<<<<<<< HEAD
    
=======
>>>>>>> 0f9be098c09a370d9b7246eec13ee77203b60875
=======
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
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
<<<<<<< HEAD
<<<<<<< HEAD
    
    
=======
>>>>>>> 0f9be098c09a370d9b7246eec13ee77203b60875

=======
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681

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
<<<<<<< HEAD
<<<<<<< HEAD
        $user = $userRepository->findOneBy(['email' => $email]);
    
        if (!$user) {
            return false; 
        }
    
        $passwordEncoder = $this->passwordEncoder;
        return $passwordEncoder->isPasswordValid($user, $password);
=======
        $validUser = $userRepository->findOneBy(['email' => $email, 'password' => $password]);
        return $validUser !== null;
>>>>>>> 0f9be098c09a370d9b7246eec13ee77203b60875
=======
        $validUser = $userRepository->findOneBy(['email' => $email, 'password' => $password]);
        return $validUser !== null;
>>>>>>> eb00ab66c5c8cb0c2ad54f78e46097d1f33bb681
    }
    
}
