<?php

namespace Tests\Feature;

use App\Mail\CustomerOtpMail;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Order;
use App\Models\CustomerActionOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OtpAccountTest extends TestCase
{
    use RefreshDatabase;

    protected function createCustomer(): Customer
    {
        return Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'contact_num' => '09123456789',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_user_can_request_account_deletion_otp(): void
    {
        Mail::fake();
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->post('/account/delete/otp', []);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('customer_action_otps', [
            'email' => $customer->email,
            'action' => 'delete',
        ]);
        Mail::assertSent(CustomerOtpMail::class);
    }

    public function test_user_cannot_request_deletion_otp_with_ongoing_transactions(): void
    {
        Mail::fake();
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $paymentMethod = \App\Models\PaymentMethod::create(['methodName' => 'Cash on Delivery']);
        $cart = \App\Models\Cart::create([
            'createdDate' => now()->toDateString(),
            'status' => 'active',
            'cus_id' => $customer->cus_id,
        ]);

        // Create an active order
        Order::create([
            'cus_id' => $customer->cus_id,
            'cart_id' => $cart->cart_id,
            'order_date' => now(),
            'order_status' => 'Processing',
            'total_amount' => 1000,
            'total_price' => 1000,
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
        ]);

        $response = $this->post('/account/delete/otp', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('otp_code');
        Mail::assertNotSent(CustomerOtpMail::class);
    }

    public function test_user_can_delete_account_with_valid_otp(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        CustomerActionOtp::create([
            'email' => $customer->email,
            'action' => 'delete',
            'code_hash' => hash('sha256', '123456'),
            'expires_at' => now()->addMinutes(5),
        ]);

        $response = $this->delete('/account/delete', [
            'otp_code' => '123456',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success', 'Your account has been deleted successfully.');
        $this->assertDatabaseMissing('customers', ['email' => 'john@example.com']);
        $this->assertGuest('customer');
    }

    public function test_user_cannot_delete_account_with_invalid_otp(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->delete('/account/delete', [
            'otp_code' => '000000',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('otp_code');
        $this->assertDatabaseHas('customers', ['email' => 'john@example.com']);
        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_user_cannot_delete_account_with_expired_otp(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        CustomerActionOtp::create([
            'email' => $customer->email,
            'action' => 'delete',
            'code_hash' => hash('sha256', '123456'),
            'expires_at' => now()->subMinute(),
        ]);

        $response = $this->delete('/account/delete', [
            'otp_code' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('otp_code');
        $this->assertDatabaseHas('customers', ['email' => 'john@example.com']);
    }

    public function test_account_deletion_cascades_to_address(): void
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

        CustomerActionOtp::create([
            'email' => $customer->email,
            'action' => 'delete',
            'code_hash' => hash('sha256', '123456'),
            'expires_at' => now()->addMinutes(5),
        ]);

        $this->delete('/account/delete', [
            'otp_code' => '123456',
        ]);

        $this->assertDatabaseMissing('addresses', [
            'add_id' => $address->add_id,
        ]);
    }

    public function test_account_deletion_otp_marks_previous_otp_as_consumed(): void
    {
        Mail::fake();
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $this->post('/account/delete/otp', []);

        $firstOtp = CustomerActionOtp::where('email', $customer->email)
            ->where('action', 'delete')
            ->first();

        $this->post('/account/delete/otp', []);

        $firstOtp->refresh();
        $this->assertNotNull($firstOtp->consumed_at);
    }

    public function test_account_deletion_otp_is_marked_consumed_after_deletion(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        CustomerActionOtp::create([
            'email' => $customer->email,
            'action' => 'delete',
            'code_hash' => hash('sha256', '123456'),
            'expires_at' => now()->addMinutes(5),
        ]);

        $otp = CustomerActionOtp::where('email', $customer->email)
            ->where('action', 'delete')
            ->first();

        $this->assertNull($otp->consumed_at);

        $this->delete('/account/delete', [
            'otp_code' => '123456',
        ]);

        $otp->refresh();
        $this->assertNotNull($otp->consumed_at);
    }

    public function test_user_cannot_delete_account_with_only_otp_code(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->delete('/account/delete', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('otp_code');
    }
}
