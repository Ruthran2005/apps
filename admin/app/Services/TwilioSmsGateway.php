<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class TwilioSmsGateway
{
    public function isConfigured(): bool
    {
        return filled(config('services.twilio.account_sid'))
            && filled(config('services.twilio.auth_token'))
            && filled(config('services.twilio.from'));
    }

    public function send(string $to, string $message): void
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Twilio is not configured. Add the account SID, auth token, and sender number.');
        }

        $accountSid = config('services.twilio.account_sid');

        Http::asForm()
            ->withBasicAuth($accountSid, config('services.twilio.auth_token'))
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                'From' => config('services.twilio.from'),
                'To' => $to,
                'Body' => $message,
            ])
            ->throw();
    }
}
