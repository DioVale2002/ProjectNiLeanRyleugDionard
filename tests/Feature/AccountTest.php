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

    public function test_guest_cannot_access_orders_page()
    {
        $response = $this->get('/account/orders');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_archived_page()
    {
        $response = $this->get('/account/archived');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_addresses_page()
    {
        $response = $this->get('/account/addresses');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_security_page()
    {
        $response = $this->get('/account/security');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_orders_page()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->get('/account/orders');
        $response->assertStatus(200);
        $response->assertViewIs('account.orders');
        $response->assertSee('No orders yet');
    }

    public function test_authenticated_user_can_view_archived_page()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->get('/account/archived');
        $response->assertStatus(200);
        $response->assertViewIs('account.archived');
        $response->assertSee('No orders yet');
    }

    public function test_authenticated_user_can_view_addresses_page()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->get('/account/addresses');
        $response->assertStatus(200);
        $response->assertViewIs('account.addresses');
    }

    public function test_authenticated_user_can_view_security_page()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->get('/account/security');
        $response->assertStatus(200);
        $response->assertViewIs('account.security');
    }

    public function test_dashboard_redirects_to_orders()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->get('/dashboard');
        $response->assertRedirect('/account/orders');
    }

    public function test_user_can_update_address()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/address/update', [
            'country' => 'Philippines',
            'province' => 'Davao del Sur',
            'city' => 'Davao City',
            'barangay' => 'Poblacion',
            'zip_postal_code' => '8000',
        ]);

        $response->assertRedirect('/account/addresses');
        $response->assertSessionHas('success', 'Address updated successfully!');

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

        $response = $this->put('/account/address/update', [
            'country' => 'Philippines',
            'province' => 'Metro Manila',
            'city' => 'Manila',
            'barangay' => 'Ermita',
            'zip_postal_code' => '1000',
        ]);

        $response->assertRedirect('/account/addresses');

        $this->assertDatabaseHas('addresses', [
            'cus_id' => $customer->cus_id,
            'city' => 'Manila',
            'zip_postal_code' => '1000',
        ]);
    }

    public function test_user_can_update_personal_information()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/info/update', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'contact_num' => '09987654321',
            'email' => 'jane@example.com',
        ]);

        $response->assertRedirect('/account/security');
        $response->assertSessionHas('success', 'Account information updated successfully!');

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

        $response = $this->put('/account/info/update', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'jane@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_update_password_in_info_update()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/info/update', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'new_password' => 'newpassword456',
        ]);

        $response->assertRedirect('/account/security');

        $customer->refresh();
        $this->assertTrue(Hash::check('newpassword456', $customer->password));
    }

    public function test_user_can_delete_account_with_correct_password()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->delete('/account/delete', [
            'password' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success', 'Your account has been deleted successfully.');

        $this->assertDatabaseMissing('customers', [
            'email' => 'john@example.com',
        ]);

        $this->assertGuest('customer');
    }

    public function test_user_cannot_delete_account_with_incorrect_password()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->delete('/account/delete', [
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseHas('customers', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_deleting_account_also_deletes_address()
    {
        $customer = $this->createCustomer();
        
        $address = Address::create([
            'cus_id' => $customer->cus_id,
            'country' => 'Philippines',
            'province' => 'Davao del Sur',
            'city' => 'Davao City',
            'barangay' => 'Poblacion',
            'zip_postal_code' => '8000',
        ]);

        $this->actingAs($customer, 'customer');

        $this->delete('/account/delete', [
            'password' => 'password123',
        ]);

        $this->assertDatabaseMissing('addresses', [
            'add_id' => $address->add_id,
        ]);
    }

    public function test_address_is_optional_on_update()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->put('/account/address/update', [
            'country' => '',
            'province' => '',
            'city' => '',
            'barangay' => '',
            'zip_postal_code' => '',
        ]);

        $response->assertRedirect('/account/addresses');
        
        $this->assertDatabaseMissing('addresses', [
            'cus_id' => $customer->cus_id,
        ]);
    }
}