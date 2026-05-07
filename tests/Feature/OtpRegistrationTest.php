<?php

namespace Tests\Feature;

use App\Mail\CustomerOtpMail;
use App\Models\Customer;
use App\Models\CustomerActionOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_registration_otp(): void
    {
        Mail::fake();

        $response = $this->post('/register/otp/request', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('customer_action_otps', [
            'email' => 'john@example.com',
            'action' => 'register',
        ]);
        Mail::assertSent(CustomerOtpMail::class);
    }

    public function test_registration_otp_rejects_invalid_email(): void
    {
        $response = $this->post('/register/otp/request', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_registration_otp_rejects_duplicate_email(): void
    {
        Customer::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'jane@example.com',
            'password' => 'hashed-password',
        ]);

        $response = $this->post('/register/otp/request', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'jane@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_verify_registration_otp(): void
    {
        CustomerActionOtp::create([
            'email' => 'john@example.com',
            'action' => 'register',
            'code_hash' => hash('sha256', '123456'),
            'expires_at' => now()->addMinutes(5),
            'payload' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'contact_num' => '09123456789',
                'email' => 'john@example.com',
            ],
        ]);

        $response = $this->post('/register/otp/verify', [
            'email' => 'john@example.com',
            'otp_code' => '123456',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('customers', [
            'email' => 'john@example.com',
            'first_name' => 'John',
        ]);
        $this->assertAuthenticatedAs(
            Customer::where('email', 'john@example.com')->first(),
            'customer'
        );
    }

    public function test_registration_otp_rejects_invalid_code(): void
    {
        CustomerActionOtp::create([
            'email' => 'john@example.com',
            'action' => 'register',
            'code_hash' => hash('sha256', '123456'),
            'expires_at' => now()->addMinutes(5),
            'payload' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'contact_num' => '09123456789',
                'email' => 'john@example.com',
            ],
        ]);

        $response = $this->post('/register/otp/verify', [
            'email' => 'john@example.com',
            'otp_code' => '000000',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('otp_code');
        $this->assertGuest('customer');
        $this->assertDatabaseMissing('customers', ['email' => 'john@example.com']);
    }

    public function test_registration_otp_rejects_expired_code(): void
    {
        CustomerActionOtp::create([
            'email' => 'john@example.com',
            'action' => 'register',
            'code_hash' => hash('sha256', '123456'),
            'expires_at' => now()->subMinute(),
            'payload' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'contact_num' => '09123456789',
                'email' => 'john@example.com',
            ],
        ]);

        $response = $this->post('/register/otp/verify', [
            'email' => 'john@example.com',
            'otp_code' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('otp_code');
        $this->assertGuest('customer');
    }

    public function test_registration_otp_rejects_already_registered_email(): void
    {
        Customer::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'jane@example.com',
            'password' => 'hashed-password',
        ]);

        CustomerActionOtp::create([
            'email' => 'jane@example.com',
            'action' => 'register',
            'code_hash' => hash('sha256', '123456'),
            'expires_at' => now()->addMinutes(5),
            'payload' => [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'contact_num' => '09987654321',
                'email' => 'jane@example.com',
            ],
        ]);

        $response = $this->post('/register/otp/verify', [
            'email' => 'jane@example.com',
            'otp_code' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_registration_marks_previous_otp_as_consumed(): void
    {
        Mail::fake();

        $this->post('/register/otp/request', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
        ]);

        $firstOtp = CustomerActionOtp::where('email', 'john@example.com')
            ->where('action', 'register')
            ->first();

        $this->post('/register/otp/request', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
        ]);

        $firstOtp->refresh();
        $this->assertNotNull($firstOtp->consumed_at);
    }
}
