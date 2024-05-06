<?php

namespace App\Controller;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Manager\ProgramManager;
use App\Repository\ProgramRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PharIo\Manifest\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email as MimeEmail;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends AbstractController
{
    #[Route('/program', name: 'app_program')]
    public function index(): Response
    {
        return $this->render('program/index.html.twig', [
            'controller_name' => 'ProgramController',
        ]);
    }
    #[Route('/addProgram', name: 'add_program')]
    public function addProgram(ManagerRegistry $manager, Request $request,ProgramRepository $programRepository): Response
    {
        $em = $manager->getManager();
        
        $program = new Program();
        
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            
            
            $existingProgram = $programRepository->findOneBy(['nom' => $program->getNom()]);

            if ($existingProgram) {
                $this->addFlash('danger', 'Program already exists!');
                return $this->redirectToRoute('add_program');
            }
            $imageFile = $form->get('imgsrc')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/Images/programs',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception
                }

                $program->setImgsrc('/Images/programs/' . $newFilename);
            }
            
            $em->persist($program);
            $em->flush();

            return $this->redirectToRoute('list_program');
        }
       
    
    return $this->renderForm('program/addProgram.html.twig', ['form' => $form]);
}
    #[Route('/listProgram', name: 'list_program')]
    public function listProgram(ProgramRepository $programrepository): Response
    {
        return $this->render('program/listProgram.html.twig', [
            'programs' => $programrepository->findAll(),
        ]);
    }
    #[Route('/editProgram/{id}', name: 'program_edit')]
    public function editProgram(Request $request, ManagerRegistry $manager, $id, ProgramRepository $programrepository): Response
    {
        $em = $manager->getManager();

        $program  = $programrepository->find($id);
        $originalDeadline = $program->getRegistrationDeadline();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            if($form->isValid()){
            $imageFile = $form->get('imgsrc')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/Images/programs',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception
                }

                $program->setImgsrc('/Images/programs/' . $newFilename);}
            $em->persist($program);
            $em->flush();
            $this->addFlash('success', 'Program updated successfully!');
            return $this->redirectToRoute('list_program');
            }
        }
        return $this->renderForm('program/editProgram.html.twig', [
            'form' => $form,
            'originalDeadline' => $originalDeadline,
            
        ]);
    }
    #[Route('/deleteprogram/{id}', name: 'program_delete')]
    public function deleteProgram( $id, ManagerRegistry $manager, ProgramRepository $programrepository): Response
    {
        $em = $manager->getManager();
        $program = $programrepository->find($id);
        if (!$program->getExercices()->isEmpty()) {
            $this->addFlash('danger', 'Program a deja des exercices');
            
        } else {
            $em->remove($program);
            $em->flush();
            $this->addFlash('success', 'Program supprimé');
        }
        return $this->redirectToRoute('list_program');
    }
    
    #[Route('/clientProgram', name: 'client_program')]
    public function clientPrograms(EntityManagerInterface $entityManager,ProgramRepository $programrepository,UserRepository $userRepository): Response
    {
        $currentDate = new DateTime();
        
        
    
        $query = $entityManager->createQuery(
            'SELECT p FROM App\Entity\Program p
            WHERE  p.registration_deadline > :currentDate'
            )->setParameter('currentDate', $currentDate);
        
        $programs = $query->getResult();

        $userSubscriptions = [];
        if ($this->getUser()) {
            $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            $userSubscriptions = $user->getInscriptions()->toArray();
        }
    
       
        $subscribedProgramIds = [];
        foreach ($userSubscriptions as $subscription) {
            $subscribedProgramIds[] = $subscription->getProgram()->getId();
        }
    
        
        return $this->render('program/clientPrograms.html.twig', [
            'programs' => $programs,
            'subscribedProgramIds' => $subscribedProgramIds,
        ]);
    }


    
#[Route('/paymentProgram/{id}/show', name:"payment", methods:["GET", "POST"])]
public function payment($id, ProgramManager $programManager, ProgramRepository $programrepository,UserRepository $userRepository): Response
{
    
       
    if (!$this->getUser()) {
        return $this->redirectToRoute('app_login');
    }
    else{
        $program = $programrepository->find($id);
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
    $isSubscribed = $programManager->isUserSubscribed($program, $user);

    if ($isSubscribed) {
        // If user is already subscribed, display flash message and redirect
        $this->addFlash('warning', 'You are already subscribed to this program.');
        return $this->redirectToRoute('client_program');
    }
    return $this->render('program/paymentProgram.html.twig', [
        'user' => $this->getUser(),
        'intentSecret' => $programManager->intentSecret($program),
        'program' => $program
    ]);
}
}


#[Route("/subscription/{id}/paiement/load", name:"subscription_paiement", methods:["GET", "POST"])]
public function subscription(
    Request $request,
    $id, ProgramManager $programManager, ProgramRepository $programrepository,MailerService $mailerService
){
    $program = $programrepository->find($id);
    $user = $this->getUser();
    if($request->getMethod() === "POST") {
        $resource = $programManager->stripe($_POST, $program);
        
        if(null !== $resource) {
            $programManager->create_subscription($resource, $program, $user);
            $mailerService->send(
                "Paiement avec succés ",
                "mohameddhia.abidi02@gmail.com",
                $user->getUserIdentifier(),
                "email/email.html.twig",
                [
                    "program" => $program               ]
            );
            return $this->render('program/reponse.html.twig', [
                'program' => $program
            ]);
        }
    }

    return $this->redirectToRoute('client_program', ['id' => $program->getId()]);
}


 #[Route("/inscriptions", name:"payment_inscriptions", methods:["GET"])]
public function payment_inscriptions(ProgramManager $programManager): Response
{
    if (!$this->getUser()) {
        return $this->redirectToRoute('app_login');
    }

    return $this->render('program/payment_story.html.twig', [
        'user' => $this->getUser(),
        'inscriptions' => $programManager->getInscriptions($this->getUser()),
        'sumInscription' => $programManager->countSoldeInscription($this->getUser()),
    ]);
}

#[Route('/userPrograms', name: 'user_programs')]
public function userPrograms(UserRepository $userRepository): Response
{
    $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

    
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // Get the inscriptions of the user
    $inscriptions = $user->getInscriptions();

    // Extract programs from inscriptions
    $programs = [];
    foreach ($inscriptions as $inscription) {
        $program = $inscription->getProgram();
        if ($program) {
            $programs[] = $program;
        }
    }

    // Render the view with the programs
    return $this->render('program/userPrograms.html.twig', [
        'programs' => $programs,
    ]);
}
#[Route('/search', name: 'search_programs')]
    public function searchPrograms(Request $request, ProgramRepository $programRepository,UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $userSubscriptions = [];
        if ($user) {
            $userSubscriptions = $user->getInscriptions()->toArray();
        }
    
       
        $subscribedProgramIds = [];
        foreach ($userSubscriptions as $subscription) {
            $subscribedProgramIds[] = $subscription->getProgram()->getId();
        }
        $partialName = $request->query->get('query');

        // Call the custom method to search for programs by partial name
        $programs = $programRepository->findByPartialName($partialName);

        // Pass the search results to the Twig template
        return $this->render('program/clientPrograms.html.twig', [
            'programs' => $programs,
            'subscribedProgramIds' => $subscribedProgramIds,
        ]);
    }
}
