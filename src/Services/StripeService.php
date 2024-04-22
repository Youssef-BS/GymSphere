<?php
namespace App\Services;

use App\Entity\Inscription;
use App\Entity\Program;

class StripeService
{
    private $privateKey;

    public function __construct()
    {
        
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        
    }
    
    /**
     * @param Program $program
     * @return \Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentIntent(Program $program)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        return \Stripe\PaymentIntent::create([
            'amount' => $program->getPrix() * 100,
            'currency' => Inscription::DEVISE,
            'payment_method_types' => ['card']
        
        ]);
    }

    public function paiement(
        $amount,
        $currency,
        $description,
        array $stripeParameter
    )
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        $payment_intent = null;
    
        try {
            if(isset($stripeParameter['stripeIntentId'])) {
                $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
            }
    
            if($payment_intent && ($payment_intent->status === 'succeeded')) {
                // Payment succeeded
            } else {
                $payment_intent->cancel();
            }
    
            return $payment_intent;
    
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle Stripe API errors
            error_log("Stripe API Error: " . $e->getMessage());
            return null;
        }
    }
    
    

    /**
     * @param array $stripeParameter
     * @param Program $program
     * @return \Stripe\PaymentIntent|null
     */
    public function stripe(array $stripeParameter, Program $program)
    {
        return $this->paiement(
            $program->getPrix() * 100,
            Inscription::DEVISE,
            $program->getNom(),
            $stripeParameter
        );
    }
}
