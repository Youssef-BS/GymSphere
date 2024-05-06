<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participation;
use App\Entity\User;
use App\Form\EventType;
use App\Manager\ProgramManager;
use App\Repository\EventRepository;
use App\Repository\ParticipationRepository;
use App\Repository\ProgramRepository;
use App\Repository\UserRepository;
use App\Services\QrcodeService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Endroid\QrCode\QrCode;
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
    public function listEvent(EventRepository $eventRepository,Request $request,ManagerRegistry $manager): Response
    {
        $em = $manager->getManager();
        $filter = $request->query->get('filter');
        $events = [];
        if ($filter === 'available') {
            $events = $eventRepository->findAvailableEvents();
        } else {
            $events = $eventRepository->findAll();
        }

       
        foreach ($events as $event) {
            if ($event->getDateDebut() < new \DateTime()) {
               
                $event->setStatus('Terminé'); 
    $em->flush();
               
            }
            else{
                $event->setStatus('Disponible');
                $em->flush();
            }
        }
    
        
    
        
        $eventschart = $eventRepository->findTop10EventsByParticipants();
    
        // Prepare data for the chart
        $labels = [];
        $participantsCount = [];
    
        foreach ($eventschart as $event) {
            $labels[] = $event->getNom();
            $participantsCount[] = $event->getNbParticipants();
        }
    
        return $this->render('event/listEvent.html.twig', [
            'events' => $events,
            'labels' => json_encode($labels),
            'participantsCount' => json_encode($participantsCount),
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
    public function clientPrograms( EntityManagerInterface $entityManager,UserRepository $userRepository): Response
    {
        
    
        // Check if user is logged in and get their subscriptions
        $userParticipations = [];
        if ($this->getUser()) {
            $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            $userParticipations = $user->getParticipations()->toArray();
        }
    
        
        $participatedEventIds = [];
        foreach ($userParticipations as $participation) {
            $participatedEventIds[] = $participation->getEvent()->getId();
        }
    
        $currentDate = new \DateTime();
    
    
        $query = $entityManager->createQuery(
            'SELECT e FROM App\Entity\Event e
            WHERE e.nb_participants < e.nb_max AND e.date_debut > :currentDate'
            )->setParameter('currentDate', $currentDate);

            $events = $query->getResult();

            return $this->render('event/clientEvents.html.twig', [
                'events' => $events,
                'participatedEventIds' => $participatedEventIds,
            ]);
    }

    #[Route('/participer/{id}', name:"app_participation")]
    public function participer($id, EventRepository $eventRepository, UserRepository $userRespository,ProgramManager $programManager): Response
    {
        
        
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $event = $eventRepository->find($id);
        if($event){ 
            $user = $userRespository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            $event->setNbParticipants($event->getNbParticipants()+1);
            $programManager->create_participation($event,$user);
            
            return $this->redirectToRoute('participations');
        }
        return $this->redirectToRoute('client_event');
        
    }


    #[Route('/participations', name:"participations")]
    public function participations( UserRepository $userRespository): Response
    {
        $user = $userRespository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $participations = $user->getParticipations();
        
        return $this->render('event/participations.html.twig', [
            'participations' => $participations,
            
        ]);
        
    }
    #[Route('/statistics', name: 'statistics')]
    public function stat(EventRepository $eventRepository): Response
    {
       
        $eventschart = $eventRepository->findTop10EventsByParticipants();

        
        $labels = [];
        $participantsCount = [];

        foreach ($eventschart as $event) {
            $labels[] = $event->getNom();
            $participantsCount[] = $event->getNbParticipants();
        }

        return $this->render('event/statistics.html.twig', [
            'labels' => json_encode($labels),
            'participantsCount' => json_encode($participantsCount),
        ]);
    }
}
