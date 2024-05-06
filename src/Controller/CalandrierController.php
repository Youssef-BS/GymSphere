<?php

namespace App\Controller;

use App\Entity\Calandrier;
use App\Form\CalandrierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Gym; 

#[Route('/calandrier')]
class CalandrierController extends AbstractController
{
    #[Route('/', name: 'app_calandrier_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $calandriers = $entityManager
            ->getRepository(Calandrier::class)
            ->findAll();

        return $this->render('calandrier/index.html.twig', [
            'calandriers' => $calandriers,
        ]);
    }

    #[Route('/new', name: 'app_calandrier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $calandrier = new Calandrier();
        $form = $this->createForm(CalandrierType::class, $calandrier);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $gymId = $request->request->get('calandrier')['idgym'];
            $gym = $entityManager->getRepository(Gym::class)->find($gymId);
    
            if (!$gym) {
                // If Gym is not found, add flash message and return to the form
                $this->addFlash('error', 'The specified Gym ID was not found.');
                return $this->renderForm('calandrier/new.html.twig', [
                    'calandrier' => $calandrier,
                    'form' => $form,
                ]);
            }

            $calandrier->setGym($gym);
            $entityManager->persist($calandrier);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_calandrier_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('calandrier/new.html.twig', [
            'calandrier' => $calandrier,
            'form' => $form,
        ]);
    }
    
    #[Route('/{idcalandrier}', name: 'app_calandrier_show', methods: ['GET'])]
    public function show(Calandrier $calandrier): Response
    {
        return $this->render('calandrier/show.html.twig', [
            'calandrier' => $calandrier,
        ]);
    }

    #[Route('/{idcalandrier}/edit', name: 'app_calandrier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calandrier $calandrier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CalandrierType::class, $calandrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_calandrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('calandrier/edit.html.twig', [
            'calandrier' => $calandrier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idcalandrier}', name: 'app_calandrier_delete', methods: ['POST'])]
    public function delete(Request $request, Calandrier $calandrier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $calandrier->getIdCalandrier(), $request->request->get('_token'))) {
            $entityManager->remove($calandrier);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_calandrier_index', [], Response::HTTP_SEE_OTHER);
    }
}
