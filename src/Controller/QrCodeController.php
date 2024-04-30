<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participation;
use App\Entity\User;
use App\Form\EventType;
use App\Manager\ProgramManager;
use App\Repository\EventRepository;
use App\Repository\ProgramRepository;
use App\Repository\UserRepository;
use App\Services\QrcodeService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Endroid\QrCode\QrCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QrCodeController extends AbstractController
{
    
   
        
        #[Route('/participationDetail/{eventId}/{userId}', name:"detail_particip")]
        public function participer($eventId, $userId, QrcodeService $qrcodeService,EntityManagerInterface $entityManager): Response
        {
            $event = $entityManager->getRepository(Event::class)->find($eventId);
            $user = $entityManager->getRepository(User::class)->find($userId);
    
            if (!$event || !$user) {
                throw $this->createNotFoundException('Event or User not found');
            }
    
            // Find existing participation by user and event
            $participation = $entityManager->getRepository(Participation::class)->findOneBy([
                'event' => $event,
                'User' => $user,
            ]);
    
            if (!$participation) {
                throw $this->createNotFoundException('Participation not found');
            }
    
            $qrCodeContent = "WELCOME TO THE EVENT " . $event->getNom() . " MONSIEUR / MADAMME " . $user->getName();
            $qrCode = $qrcodeService->qrcode($qrCodeContent);
            
            // Update the QR code of the existing participation
            $participation->setQrcode($qrCode); // Assuming qrcode is a string property
            $entityManager->flush();
    
            return $this->render('event/participate.html.twig', [
                'event' => $event->getNom(),
                'qrCode' => $qrCode,
            ]);
        }
    }
    

