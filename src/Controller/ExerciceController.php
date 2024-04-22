<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Form\ExerciceType;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExerciceController extends AbstractController
{
    #[Route('/listExercice', name: 'list_exercice')]
    public function listExercice(ExerciceRepository $exerciceRepository): Response
    {
        return $this->render('exercice/listExercice.html.twig', [
            'exercices' => $exerciceRepository->findAll(),
        ]);
    }

    #[Route('/addExercice', name: 'add_exercice')]
    public function addExercice(Request $request, EntityManagerInterface $entityManager,ExerciceRepository $exerciceRepository): Response
    {
        $exercice = new Exercice();
        $form = $this->createForm(ExerciceType::class, $exercice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $existingEvent = $exerciceRepository->findOneBy(['nom' => $exercice->getNom()]);

            if ($existingEvent) {
                $this->addFlash('danger', 'Exercice already exists!');
                return $this->redirectToRoute('add_exercice');
            }            
            $videoFile = $form->get('videosrc')->getData();

            if ($videoFile) {
                $originalVideoName = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newVideoName = $originalVideoName.'-'.uniqid().'.'.$videoFile->guessExtension();

                try {
                    $videoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/videos/exercices',
                        $newVideoName
                    );
                } catch (FileException $e) {
                    // handle exception
                }

                $exercice->setVideo('/Videos/exercices/' . $newVideoName);
            }
            $entityManager->persist($exercice);
            $entityManager->flush();

            return $this->redirectToRoute('list_exercice');
        }

        return $this->render('exercice/addExercice.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/editExercice/{id}', name: 'exercice_edit')]
    public function editExercice(Request $request, ManagerRegistry $manager, $id, ExerciceRepository $exerciceRepository): Response
    {
        $em = $manager->getManager();

        $exercice = $exerciceRepository->find($id);
        $form = $this->createForm(ExerciceType::class, $exercice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $videoFile = $form->get('videosrc')->getData();
            if ($videoFile) {
                $originalVideoName = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newVideoName = $originalVideoName.'-'.uniqid().'.'.$videoFile->guessExtension();

                try {
                    $videoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/videos/exercices',
                        $newVideoName
                    );
                } catch (FileException $e) {
                    // handle exception
                }

                $exercice->setVideo('/Videos/exercices/' . $newVideoName);
            } 
        
        $em->persist($exercice);
        $em->flush();
        $this->addFlash('success', 'Exercice updated successfully!');
            return $this->redirectToRoute('list_exercice');
        }

        return $this->render('exercice/editExercice.html.twig', [
            'exercice'=> $exercice,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deleteExercice/{id}', name: 'exercice_delete')]
    public function deleteExercice( ManagerRegistry $manager, $id,ExerciceRepository $exerciceRepository): Response
    {
        $em = $manager->getManager();
        $exercice = $exerciceRepository->find($id);
        if($exercice){
        $em->remove($exercice);
        $em->flush();
        $this->addFlash('success', 'Exercice supprimé');
        }
        return $this->redirectToRoute('list_exercice');
    }
    #[Route('/clientExercice/{id}', name: 'client_exercice')]
    public function clientPrograms(ExerciceRepository $exercicerepository, $id): Response
    {
        $exercices = $exercicerepository->findBy(['Program' => $id]);
        return $this->render('exercice/clientExercices.html.twig', [
            'exercices' => $exercices,
        ]);
    }
    #[Route('/clientExercices', name: 'client_exercicess')]
    public function clientProgram(ExerciceRepository $exercicerepository): Response
    {
        $exercices = $exercicerepository->findAll();
        return $this->render('exercice/clientExercices.html.twig', [
            'exercices' => $exercices
        ]);
    }
}