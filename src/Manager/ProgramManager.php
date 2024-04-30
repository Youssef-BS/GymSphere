<?php

namespace App\Manager;

use App\Entity\Event;
use App\Entity\Inscription;
use App\Entity\Participation;
use App\Entity\Program;
use App\Entity\User;
use App\Services\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProgramManager
{
    
    private $privateKey;

   
    
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param StripeService $stripeService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        StripeService $stripeService
    ) {
        $this->em = $entityManager;
        $this->stripeService = $stripeService;
        $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
    }

    public function getPrograms()
    {
        return $this->em->getRepository(Program::class)
            ->findAll();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function countSoldeInscription(User $user)
    {
        return $this->em->getRepository(Inscription::class)
            ->countSoldeInscription($user);
    }

    public function getInscriptions(User $user)
    {
        return $this->em->getRepository(Inscription::class)
            ->findByUser($user);
    }

    public function intentSecret(Program $program)
    {
        $intent = $this->stripeService->paymentIntent($program);

        return $intent['client_secret'] ?? null;
    }

    /**
     * @param array $stripeParameter
     * @param Program $program
     * @return array|null
     */
    public function stripe(array $stripeParameter, Program $program)
{
    \Stripe\Stripe::setApiKey($this->privateKey);
    $resource = null;

    try {
        $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);

        if ($payment_intent && $payment_intent->status === 'succeeded') {
            $payment_method = \Stripe\PaymentMethod::retrieve($payment_intent->payment_method);
            $charge = \Stripe\Charge::retrieve($payment_intent->latest_charge);

            $resource = [
                'stripeBrand' => $payment_method->card->brand,
                'stripeLast4' => $payment_method->card->last4,
                'stripeId' => $charge->id,
                'stripeStatus' => $charge->status,
                'stripeToken' => $payment_intent->client_secret
            ];
        }

        return $resource;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle Stripe API errors
        error_log("Stripe API Error: " . $e->getMessage());
        return null;
    }
}

    

    /**
     * @param array $resource
     * @param Program $program
     * @param User $user
     */
    public function create_subscription(array $resource, Program $program, User $user)
    {
        $inscription = new Inscription();
        $inscription->setUser($user);
        $inscription->setProgram($program);
        $inscription->setBrandStripe($resource['stripeBrand']);
        $inscription->setLast4Stripe($resource['stripeLast4']);
        $inscription->setIdChargeStripe($resource['stripeId']);
        $inscription->setStripeToken($resource['stripeToken']);
        $inscription->setStatusStripe($resource['stripeStatus']);
        $inscription->setUpdatedAt(new \Datetime());
        $inscription->setCreatedAt(new \Datetime());
        $this->em->persist($inscription);
        $this->em->flush();
    }

    public function isUserSubscribed(Program $program, User $user): bool
    {
        $inscriptionRepository = $this->em->getRepository('App\Entity\Inscription');
        $inscription = $inscriptionRepository->findOneBy(['program' => $program, 'user' => $user]);

        return $inscription !== null;
    }
    /**
     * @param Event $event
     * @param User $user
     */
    public function create_participation(Event $event, User $user)
    {
        $participation = new Participation();
        $participation->setUser($user);
        $participation->setEvent($event);
        $this->em->persist($participation);
        $this->em->flush();
    }
}
