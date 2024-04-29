<?php

namespace App\Controller;

use App\Entity\Abonnement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbonnementController extends AbstractController
{
    #[Route('/abonnements', name: 'abonnement_index', methods: ['GET'])]
    public function index(): Response
    {
        $abonnements = $this->getDoctrine()->getRepository(Abonnement::class)->findAll();        
        return $this->render('admin/component/abn.html.twig', [
            'abonnements' => $abonnements
        ]);
    }

    #[Route('/abonnements', name: 'abonnement_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $abonnement = new Abonnement();
        $abonnement->setDateDebut(new \DateTime($data['date_debut']));
        $abonnement->setDateFin(new \DateTime($data['date_fin']));
        $abonnement->setPrix($data['prix']);
        $abonnement->setPayment($data['payment']);
        $abonnement->setIdUser($data['id_user']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonnement);
        $entityManager->flush();

        return $this->json($abonnement);
    }

    #[Route('/abonnements/{id}', name: 'abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement): Response
    {
        return $this->json($abonnement);
    }

    #[Route('/abonnements/{id}', name: 'abonnement_update', methods: ['PUT'])]
    public function update(Request $request, Abonnement $abonnement): Response
    {
        $data = json_decode($request->getContent(), true);

        $abonnement->setDateDebut(new \DateTime($data['date_debut']));
        $abonnement->setDateFin(new \DateTime($data['date_fin']));
        $abonnement->setPrix($data['prix']);
        $abonnement->setPayment($data['payment']);
        $abonnement->setIdUser($data['id_user']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->json($abonnement);
    }

    #[Route('/abonnements/{id}', name: 'abonnement_delete', methods: ['DELETE'])]
    public function delete(Abonnement $abonnement): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($abonnement);
        $entityManager->flush();

        return $this->json(['message' => 'Abonnement deleted']);
    }
}
