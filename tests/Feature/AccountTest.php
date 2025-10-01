<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected function createCustomer()
    {
        return Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_guest_cannot_access_edit_account_page()
    {
        $response = $this->get('/account/edit');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_edit_account_page()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->get('/account/edit');
        $response->assertStatus(200);
        $response->assertViewIs('account.edit');
    }

    public function test_user_can_update_account_information()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/update', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'contact_num' => '09987654321',
            'email' => 'jane@example.com',
        ]);

        $response->assertRedirect('/account/edit');
        $response->assertSessionHas('success', 'Account updated successfully!');

        $this->assertDatabaseHas('customers', [
            'cus_id' => $customer->cus_id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
        ]);
    }

    public function test_user_cannot_update_email_to_existing_email()
    {
        $customer1 = $this->createCustomer();
        
        $customer2 = Customer::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'contact_num' => '09987654321',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($customer1, 'customer');

        $response = $this->put('/account/update', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'jane@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_add_address()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/update', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'country' => 'Philippines',
            'province' => 'Davao del Sur',
            'city' => 'Davao City',
            'barangay' => 'Poblacion',
            'zip_postal_code' => '8000',
        ]);

        $response->assertRedirect('/account/edit');

        $this->assertDatabaseHas('addresses', [
            'cus_id' => $customer->cus_id,
            'country' => 'Philippines',
            'city' => 'Davao City',
        ]);
    }

    public function test_user_can_update_existing_address()
    {
        $customer = $this->createCustomer();
        
        Address::create([
            'cus_id' => $customer->cus_id,
            'country' => 'Philippines',
            'province' => 'Davao del Sur',
            'city' => 'Davao City',
            'barangay' => 'Poblacion',
            'zip_postal_code' => '8000',
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/update', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'country' => 'Philippines',
            'province' => 'Metro Manila',
            'city' => 'Manila',
            'barangay' => 'Ermita',
            'zip_postal_code' => '1000',
        ]);

        $response->assertRedirect('/account/edit');

        $this->assertDatabaseHas('addresses', [
            'cus_id' => $customer->cus_id,
            'city' => 'Manila',
            'zip_postal_code' => '1000',
        ]);
    }

    public function test_user_can_update_password_with_correct_current_password()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/password', [
            'current_password' => 'password123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertRedirect('/account/edit');
        $response->assertSessionHas('success', 'Password updated successfully!');

        $customer->refresh();
        $this->assertTrue(Hash::check('newpassword456', $customer->password));
    }

    public function test_user_cannot_update_password_with_incorrect_current_password()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_user_cannot_update_password_without_confirmation()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/password', [
            'current_password' => 'password123',
            'password' => 'newpassword456',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_address_is_optional_on_update()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/update', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'country' => '',
            'province' => '',
            'city' => '',
            'barangay' => '',
            'zip_postal_code' => '',
        ]);

        $response->assertRedirect('/account/edit');
        
        $this->assertDatabaseMissing('addresses', [
            'cus_id' => $customer->cus_id,
        ]);
    }
}
