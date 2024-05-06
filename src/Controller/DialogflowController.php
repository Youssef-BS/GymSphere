<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

class DialogflowController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/dialogflow/webhook', name: 'dialogflow_webhook', methods: ['POST'])]
    public function webhook(Request $request): Response
    {
        try {
            $content = $request->getContent();
            $data = json_decode($content, true);

            if (isset($data['queryResult']['intent']['displayName'])) {
                $intentName = $data['queryResult']['intent']['displayName'];
                $parameters = $data['queryResult']['parameters'];
                $responseText = $this->handleIntent($intentName, $parameters);
            } else {
                $responseText = "I'm sorry, I couldn't understand your query. Could you please rephrase?";
            }

            $responseData = [
                'fulfillmentText' => $responseText
            ];

            return $this->json($responseData);
        } catch (\Exception $e) {
            $errorMessage = "An error occurred while processing your request. Please try again later.";
            $this->logger->error('Error processing webhook request: ' . $e->getMessage());
            return new Response($errorMessage, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function handleIntent(string $intentName, array $parameters): string
    {
        $query = $parameters['query'] ?? null;

        if ($query !== null) {
            return $query;
        } else {
            return "I'm sorry, I couldn't understand your query. Could you please rephrase?";
        }
    }
}
