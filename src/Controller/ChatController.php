<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index(): Response
    {
        $websocketUrl = 'http://localhost:5000'; // Replace with your WebSocket server URL
        
        return $this->render('admin/component/messangerAdmin.html', [
            'websocket_url' => $websocketUrl,
        ]);
    }
}
