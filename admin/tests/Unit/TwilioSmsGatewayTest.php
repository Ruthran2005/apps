<?php

namespace Tests\Unit;

use App\Services\TwilioSmsGateway;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class TwilioSmsGatewayTest extends TestCase
{
    public function test_it_sends_sms_through_the_configured_twilio_account(): void
    {
        config([
            'services.twilio.account_sid' => 'AC123',
            'services.twilio.auth_token' => 'test-token',
            'services.twilio.from' => '+15551234567',
        ]);
        Http::fake([
            'api.twilio.com/*' => Http::response(['sid' => 'SM123'], 201),
        ]);

        app(TwilioSmsGateway::class)->send('+919876543210', 'School notice');

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
                && $request['From'] === '+15551234567'
                && $request['To'] === '+919876543210'
                && $request['Body'] === 'School notice';
        });
    }

    public function test_it_does_not_send_when_twilio_is_not_configured(): void
    {
        config([
            'services.twilio.account_sid' => null,
            'services.twilio.auth_token' => null,
            'services.twilio.from' => null,
        ]);
        Http::fake();

        $this->expectException(RuntimeException::class);

        app(TwilioSmsGateway::class)->send('+919876543210', 'School notice');
    }

    public function test_it_surfaces_twilio_api_failures(): void
    {
        config([
            'services.twilio.account_sid' => 'AC123',
            'services.twilio.auth_token' => 'test-token',
            'services.twilio.from' => '+15551234567',
        ]);
        Http::fake([
            'api.twilio.com/*' => Http::response(['message' => 'Invalid number'], 400),
        ]);

        $this->expectException(RequestException::class);

        app(TwilioSmsGateway::class)->send('+919876543210', 'School notice');
    }
}
