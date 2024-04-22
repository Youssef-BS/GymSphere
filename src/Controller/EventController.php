<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\ProgramRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    #[Route('/addEvent', name: 'add_event')]
    public function addEvent(ManagerRegistry $manager, Request $request,EventRepository $eventRepository): Response
    {
        $em = $manager->getManager();

        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingEvent = $eventRepository->findOneBy(['nom' => $event->getNom()]);

            if ($existingEvent) {
                $this->addFlash('danger', 'Event already exists!');
                return $this->redirectToRoute('add_event');
            }
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('list_event');
        }

        return $this->renderForm('event/addEvent.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/listEvent', name: 'list_event')]
    public function listEvent(EventRepository $eventRepository): Response
    {
        return $this->render('event/listEvent.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/editEvent/{id}', name: 'event_edit')]
    public function editEvent(Request $request, ManagerRegistry $manager, $id, EventRepository $eventRepository): Response
    {
        $em = $manager->getManager();

        $event = $eventRepository->find($id);
        $dateDebut = $event->getDateDebut();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Event updated successfully!');
            return $this->redirectToRoute('list_event');
        }

        return $this->renderForm('event/editEvent.html.twig', [
            'dateDebut' => $dateDebut,
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/deleteEvent/{id}', name: 'event_delete')]
    public function deleteEvent( $id, ManagerRegistry $manager, EventRepository $eventRepository): Response
    {
        $em = $manager->getManager();
        $event = $eventRepository->find($id);

        if ($event) {
            $em->remove($event);
            $em->flush();
            $this->addFlash('success', 'Event supprimé');
        }

        return $this->redirectToRoute('list_event');
    }
    #[Route('/clientEvent', name: 'client_event')]
    public function clientPrograms(EventRepository $eventrepository): Response
    {
        return $this->render('event/clientEvents.html.twig', [
            'events' => $eventrepository->findAll(),
        ]);
    }
}
