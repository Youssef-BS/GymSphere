<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notifications', name: 'notifications')]
    public function notifications(Request $request): Response
    {
        $notifications = [
            ['message' => 'Notification 1'],
            ['message' => 'Notification 2'],
        
        ];

        return $this->json($notifications);
    }
}