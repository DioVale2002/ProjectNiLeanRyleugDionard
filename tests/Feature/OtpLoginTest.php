<?php

namespace Tests\Feature;

use App\Mail\LoginOtpMail;
use App\Models\Customer;
use App\Models\CustomerLoginOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OtpLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function createCustomer(): Customer
    {
        return Customer::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_user_can_request_otp(): void
    {
        Mail::fake();
        $customer = $this->createCustomer();

        $response = $this->post('/login/otp/request', [
            'email' => $customer->email,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('customer_login_otps', [
            'email' => $customer->email,
        ]);
        Mail::assertSent(LoginOtpMail::class);
    }

    public function test_otp_rejects_expired_code(): void
    {
        $customer = $this->createCustomer();

        CustomerLoginOtp::create([
            'email' => $customer->email,
            'code_hash' => hash('sha256', '654321'),
            'expires_at' => now()->subMinute(),
        ]);

        $response = $this->post('/login/otp', [
            'email' => $customer->email,
            'otp_code' => '654321',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('otp_code');
        $this->assertGuest('customer');
    }

    public function test_user_cannot_login_with_nonexistent_email(): void
    {
        $response = $this->post('/login/otp/request', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_user_cannot_verify_otp_with_nonexistent_email(): void
    {
        $response = $this->post('/login/otp', [
            'email' => 'nonexistent@example.com',
            'otp_code' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_user_cannot_login_with_invalid_otp(): void
    {
        $customer = $this->createCustomer();

        CustomerLoginOtp::create([
            'email' => $customer->email,
            'code_hash' => hash('sha256', '123456'),
            'expires_at' => now()->addMinutes(5),
        ]);

        $response = $this->post('/login/otp', [
            'email' => $customer->email,
            'otp_code' => '000000',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('otp_code');
        $this->assertGuest('customer');
    }

    public function test_user_can_login_with_valid_otp(): void
    {
        Mail::fake();
        $customer = $this->createCustomer();

        $response = $this->post('/login/otp/request', [
            'email' => $customer->email,
        ]);

        $response->assertStatus(302);
        Mail::assertSent(LoginOtpMail::class);

        $otp = CustomerLoginOtp::where('email', $customer->email)
            ->whereNull('consumed_at')
            ->first();

        // Extract the code from hash (we'll need to verify via the OTP table)
        $code = '123456'; // We need to use a known code
        CustomerLoginOtp::where('email', $customer->email)
            ->update(['code_hash' => hash('sha256', $code)]);

        $response = $this->post('/login/otp', [
            'email' => $customer->email,
            'otp_code' => $code,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_otp_code_is_marked_consumed_after_login(): void
    {
        $customer = $this->createCustomer();
        $code = '123456';

        CustomerLoginOtp::create([
            'email' => $customer->email,
            'code_hash' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(5),
        ]);

        $otp = CustomerLoginOtp::where('email', $customer->email)->first();
        $this->assertNull($otp->consumed_at);

        $this->post('/login/otp', [
            'email' => $customer->email,
            'otp_code' => $code,
        ]);

        $otp->refresh();
        $this->assertNotNull($otp->consumed_at);
    }

    public function test_otp_rejects_consumed_code(): void
    {
        $customer = $this->createCustomer();
        $code = '123456';

        CustomerLoginOtp::create([
            'email' => $customer->email,
            'code_hash' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(5),
            'consumed_at' => now(),
        ]);

        $response = $this->post('/login/otp', [
            'email' => $customer->email,
            'otp_code' => $code,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('otp_code');
        $this->assertGuest('customer');
    }
}
