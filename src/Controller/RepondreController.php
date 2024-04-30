<?php

namespace App\Controller;

use App\Entity\Repondre;
use App\Entity\User;
use App\Entity\Reclamation;

use App\Form\RepondreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Twilio\Rest\Client;






#[Route('/repondre')]
class RepondreController extends AbstractController
{
    #[Route('/admin/reclamation/{id}', name: 'app_repondre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager , int $id): Response
    {
        $repondres = $entityManager
            ->getRepository(Repondre::class)
            ->findBy(['idreclamation' => $id]);

        return $this->render('repondre/index.html.twig', [
            'repondres' => $repondres,
            'id' => $id,

        ]);
    }
    
    #[Route('/Client/reclamation/{id}/{user_id}', name: 'app_client_Response', methods: ['GET'])]
    public function indexClient(EntityManagerInterface $entityManager , int $id  , int $user_id): Response
    {
        $repondres = $entityManager
            ->getRepository(Repondre::class)
            ->findBy(['idreclamation' => $id]);

        return $this->render('reclamation/ClientView/ResponseClient.html.twig', [
            'repondres' => $repondres,
            'id' => $id,
            'user_id' => $user_id,


        ]);
    }

    #[Route('/new/{id}/{user_id}', name: 'app_repondre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $id, int $user_id): Response
    {
        // Fetch the reclamation entity from the database
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);
        if (!$reclamation) {
            throw $this->createNotFoundException('No reclamation found for id ' . $id);
        }
    
        // Create a new 'Repondre' entity and setup the form
        $repondre = new Repondre();
        $form = $this->createForm(RepondreType::class, $repondre);
        $form->handleRequest($request);
    
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $repondre->setReclamation($reclamation);
            $repondre->setIduser($user_id);
            $entityManager->persist($repondre);
            $entityManager->flush();
    
            // Setup Twilio client using environment variables
            $sid = $_ENV['TWILIO_SID'] ?? getenv('TWILIO_SID');
            $token = $_ENV['TWILIO_TOKEN'] ?? getenv('TWILIO_TOKEN');
            $twilio = new Client($sid, $token);
    
            // Try to send an SMS
            try {
                $twilio->messages->create(
                    "+21654828257", // Replace with the recipient's number
                    [
                        "from" => "+12184504891", // Your Twilio number
                        "body" => "Votre réclamation a été traité avec succès."
                    ]
                );
                return $this->redirectToRoute('reclamation', ['user_id' => $user_id]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'envoi du SMS: ' . $e->getMessage());
            }
        }
    
        // Render the form for the user to fill in again
        return $this->renderForm('repondre/new.html.twig', [
            'repondre' => $repondre,
            'form' => $form,
            'user_id' => $user_id,
        ]);
    }
    


      /**
     * @Route("/repondre/{id}", name="repondre_show")
     * @ParamConverter("repondre", class="App\Entity\Repondre")
     */

    #[Route('/{id}', name: 'app_repondre_show', methods: ['GET'])]
    public function show(Repondre $repondre): Response
    {


        if (!$repondre) {
            throw $this->createNotFoundException('Repondre not found');
        }

      




        return $this->render('repondre/show.html.twig', [
            'repondre' => $repondre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_repondre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repondre $repondre, EntityManagerInterface $entityManager , int $id): Response
    {
        $form = $this->createForm(RepondreType::class, $repondre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reclamation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('repondre/edit.html.twig', [
            'repondre' => $repondre,
            'form' => $form,
            'id' => $id,

        ]);
    }

    #[Route('/{id}', name: 'app_repondre_delete', methods: ['POST'])]
    public function delete(Request $request, Repondre $repondre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repondre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($repondre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reclamation', [], Response::HTTP_SEE_OTHER);
    }





    #[Route('/{id}/respond', name: 'app_reclamation_respond', methods: ['GET', 'POST'])]
    public function respond(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager, Client $twilio): Response
    {
        // Assuming you have configured your TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN, and TWILIO_FROM_NUMBER in your .env file
    
        // Create form and handle request
        $form = $this->createForm(ReponseType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the decision from the form
            $decision = $form->get('decision')->getData();
    
            // Assuming Reponse is your entity for storing responses
            $reponse = new Reponse();
            $reponse->setIdReclamation($reclamation); // Set the reclamation for the response
            $reponse->setDecision($decision); // Set the decision
    
            // Persist response entity
            $entityManager->persist($reponse);
            $entityManager->flush();
    
            // Send WhatsApp message
            $recipientNumber = $_ENV['TWILIO_TO_NUMBER'];
            $messageBody = "Decision: $decision\n";
            $messageBody .= "Your Complaint Details:\n";
            $messageBody .= "ID: " . $reclamation->getId() . "\n";
            $messageBody .= "Description: " . $reclamation->getObjet() . "\n";
    
            $message = $twilio->messages->create(
                "whatsapp:" . $recipientNumber,
                [
                    "from" => "whatsapp:" . $_ENV['TWILIO_FROM_NUMBER'],
                    "body" => $messageBody
                ]
            );
    
            // Redirect to response index page or any other page as needed
            return $this->redirectToRoute('app_reponse_index');
        }
    
        // Render form template with reclamation and form
        return $this->renderForm('reponse/respond.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    










}
