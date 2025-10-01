<?php

namespace Tests\Feature;

use App\Models\Customer;
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
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('customers', [
            'email' => 'john@example.com',
            'first_name' => 'John',
        ]);
    }

    public function test_user_cannot_register_with_invalid_email()
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
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

        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
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
        $response->assertStatus(200);
    }
}
