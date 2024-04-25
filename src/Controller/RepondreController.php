<?php

namespace App\Controller;

use App\Entity\Repondre;
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
    #[Route('/', name: 'app_repondre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repondres = $entityManager
            ->getRepository(Repondre::class)
            ->findAll();

        return $this->render('repondre/index.html.twig', [
            'repondres' => $repondres,
        ]);
    }

    #[Route('/new', name: 'app_repondre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repondre = new Repondre();
        $form = $this->createForm(RepondreType::class, $repondre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($repondre);
            $entityManager->flush();

            return $this->redirectToRoute('app_repondre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('repondre/new.html.twig', [
            'repondre' => $repondre,
            'form' => $form,
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
    public function edit(Request $request, Repondre $repondre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RepondreType::class, $repondre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_repondre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('repondre/edit.html.twig', [
            'repondre' => $repondre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_repondre_delete', methods: ['POST'])]
    public function delete(Request $request, Repondre $repondre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repondre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($repondre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_repondre_index', [], Response::HTTP_SEE_OTHER);
    }





    #[Route('/{id}/respond', name: 'app_reclamation_respond', methods: ['GET', 'POST'])]
    public function respond(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager, SmsGenerator $smsGenerator): Response
    {
        $number = $_ENV['TWILIO_TO_NUMBER'];
        $messageBody = 'Merci pour votre réponse.';
        $smsGenerator->sendSms($number, $messageBody);

        // Assuming Reponse is your entity for storing responses
        $reponse = new Reponse();
        $reponse->setIdReclamation($reclamation); // Set the reclamation for the response

        // Create form and handle request
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the decision from the form
            $decision = $form->get('decision')->getData();

            // Prepare the message content including the decision and reclamation details
            $messageBody = "Decision: $decision\n";
            $messageBody .= "Your Complaint Details:\n";
            $messageBody .= "ID: " . $reclamation->getId() . "\n";
            $messageBody .= "Description: " . $reclamation->getObjet() . "\n";
            // Add more fields as needed

            // Send WhatsApp message
            $message = $twilio->messages
                ->create("whatsapp:+21698715915", [
                    "from" => "whatsapp:+14155238886",
                    "body" => $messageBody
                ]);

            // Persist response entity
            $entityManager->persist($reponse);
            $entityManager->flush();

            // Redirect to response index page
            return $this->redirectToRoute('app_reponse_index');
        }

        // Render form template with reclamation and form
        return $this->renderForm('reponse/respond.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }











}
