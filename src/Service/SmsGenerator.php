<?php

// src/Service/SmsGenerator.php

namespace App\Service;

use Twilio\Rest\Client;

class SmsGenerator
{
    private $twilioSid;
    private $twilioToken;
    private $twilioFromNumber;

    public function __construct($twilioSid, $twilioToken, $twilioFromNumber)
    {
        $this->twilioSid = $twilioSid;
        $this->twilioToken = $twilioToken;
        $this->twilioFromNumber = $twilioFromNumber;
    }

    public function sendSms($toNumber, $body)
    {
        $client = new Client($this->twilioSid, $this->twilioToken);

        $message = $client->messages->create(
            $toNumber,
            [
                'from' => $this->twilioFromNumber,
                'body' => $body,
            ]
        );

        return $message;
    }


    
}
