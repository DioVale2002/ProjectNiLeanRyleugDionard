<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerLoginOtp;
use App\Models\CustomerActionOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_register_page()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post('/register/otp/request', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('customer_action_otps', [
            'email' => 'john@example.com',
            'action' => 'register',
        ]);
    }

    public function test_user_cannot_register_with_invalid_email()
    {
        $response = $this->post('/register/otp/request', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_cannot_register_with_duplicate_email()
    {
        Customer::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/register/otp/request', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'jane@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_user_can_login_with_valid_otp()
    {
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $code = '123456';
        CustomerLoginOtp::create([
            'email' => $customer->email,
            'code_hash' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(5),
        ]);

        $response = $this->post('/login/otp', [
            'email' => 'john@example.com',
            'otp_code' => $code,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_user_cannot_login_with_invalid_otp()
    {
        Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login/otp', [
            'email' => 'john@example.com',
            'otp_code' => '000000',
        ]);

        $response->assertSessionHasErrors('otp_code');
        $this->assertGuest('customer');
    }

    public function test_user_can_logout()
    {
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest('customer');
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->get('/dashboard');
        $response->assertRedirect('/account/orders');
    }

    public function test_user_can_confirm_registration_with_otp()
    {
        CustomerActionOtp::create([
            'email' => 'john@example.com',
            'action' => 'register',
            'code_hash' => hash('sha256', '112233'),
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
            'otp_code' => '112233',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('customers', [
            'email' => 'john@example.com',
        ]);
    }
}
