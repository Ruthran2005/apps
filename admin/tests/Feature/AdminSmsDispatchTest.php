<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminSmsDispatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_send_a_single_sms_to_an_e164_number(): void
    {
        $this->configureTwilio();
        Http::fake([
            'api.twilio.com/*' => Http::response(['sid' => 'SM123'], 201),
        ]);
        $this->actingAs($this->createAdmin());

        $response = $this->post(route('admin.notifications.send'), [
            'channel' => 'sms',
            'target_group' => 'single',
            'recipient_phone' => '+919876543210',
            'title' => 'School notice',
            'message' => 'School closes at 2 PM.',
        ]);

        $response->assertRedirect(route('admin.notifications'));
        $response->assertSessionHas('success', 'SMS accepted by Twilio for 1 of 1 recipient(s).');
        $this->assertDatabaseHas('sent_notifications', [
            'recipient_phone' => '+919876543210',
            'recipient_count' => 1,
            'sent_count' => 1,
            'failed_count' => 0,
            'status' => 'sent',
        ]);
        Http::assertSentCount(1);
    }

    public function test_bulk_sms_deduplicates_numbers_and_tracks_recipients_without_valid_phones(): void
    {
        $this->configureTwilio();
        Http::fake([
            'api.twilio.com/*' => Http::response(['sid' => 'SM123'], 201),
        ]);
        $this->actingAs($this->createAdmin());

        $schoolClass = SchoolClass::create(['name' => 'Grade 6', 'code' => 'G6']);
        $this->createStudent($schoolClass, 'one@example.test', '+919876543210', '+919876543210');
        $this->createStudent($schoolClass, 'two@example.test', '+919876543211', '+919876543210');
        $this->createStudent($schoolClass, 'three@example.test', 'invalid-number', null);

        $response = $this->post(route('admin.notifications.send'), [
            'channel' => 'sms',
            'target_group' => 'students',
            'title' => 'School notice',
            'message' => 'School closes at 2 PM.',
        ]);

        $response->assertRedirect(route('admin.notifications'));
        $response->assertSessionHas('success', 'SMS accepted by Twilio for 1 of 2 recipient(s).');
        $this->assertDatabaseHas('sent_notifications', [
            'target_group' => 'students',
            'recipient_count' => 2,
            'sent_count' => 1,
            'failed_count' => 1,
            'status' => 'sent',
        ]);
        Http::assertSentCount(1);
    }

    public function test_sms_is_not_recorded_as_sent_without_twilio_credentials(): void
    {
        config([
            'services.twilio.account_sid' => null,
            'services.twilio.auth_token' => null,
            'services.twilio.from' => null,
        ]);
        $this->actingAs($this->createAdmin());

        $response = $this->from(route('admin.notifications'))->post(route('admin.notifications.send'), [
            'channel' => 'sms',
            'target_group' => 'single',
            'recipient_phone' => '+919876543210',
            'title' => 'School notice',
            'message' => 'School closes at 2 PM.',
        ]);

        $response->assertRedirect(route('admin.notifications'));
        $response->assertSessionHasErrors('sms');
        $this->assertDatabaseCount('sent_notifications', 0);
    }

    private function configureTwilio(): void
    {
        config([
            'services.twilio.account_sid' => 'AC123',
            'services.twilio.auth_token' => 'test-token',
            'services.twilio.from' => '+15551234567',
        ]);
    }

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }

    private function createStudent(SchoolClass $schoolClass, string $email, string $phone, ?string $guardianPhone): void
    {
        $user = User::create([
            'name' => 'Student',
            'email' => $email,
            'password' => 'password',
            'role' => 'student',
            'phone' => $phone,
        ]);

        Student::create([
            'user_id' => $user->id,
            'admission_number' => strtoupper(substr(md5($email), 0, 12)),
            'school_class_id' => $schoolClass->id,
            'guardian_phone' => $guardianPhone,
        ]);
    }
}
