<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminOrderEventTest extends TestCase
{
    use RefreshDatabase;

    protected function createCustomer(array $overrides = []): Customer
    {
        return Customer::create(array_merge([
            'first_name'  => 'Jane',
            'last_name'   => 'Doe',
            'contact_num' => '09123456789',
            'email'       => 'jane' . uniqid() . '@example.com',
            'password'    => Hash::make('password123'),
        ], $overrides));
    }

    protected function createPaymentMethod(): PaymentMethod
    {
        return PaymentMethod::create(['methodName' => 'Cash on Delivery']);
    }

    protected function createCart(Customer $customer): Cart
    {
        return Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);
    }

    protected function createOrder(Customer $customer, Cart $cart, PaymentMethod $paymentMethod, array $overrides = []): Order
    {
        return Order::create(array_merge([
            'order_status'     => 'Pending',
            'order_date'       => now()->toDateString(),
            'total_price'      => 100,
            'is_first_party_delivery' => true,
            'delivery_status' => 'Preparing',
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
            'cus_id'           => $customer->cus_id,
            'cart_id'          => $cart->cart_id,
        ], $overrides));
    }

    public function test_admin_can_start_processing(): void
    {
        $customer = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $order = $this->createOrder($customer, $this->createCart($customer), $paymentMethod, [
            'order_status' => 'Pending',
        ]);

        $response = $this->patch("/admin/orders/{$order->order_id}/event", [
            'event' => 'start_processing',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'order_status' => 'Processing',
        ]);
    }

    public function test_admin_can_ship_and_deliver(): void
    {
        $customer = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $order = $this->createOrder($customer, $this->createCart($customer), $paymentMethod, [
            'order_status' => 'Processing',
        ]);

        $this->patch("/admin/orders/{$order->order_id}/event", [
            'event' => 'ship',
        ])->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'order_status' => 'Shipped',
        ]);

        $this->patch("/admin/orders/{$order->order_id}/event", [
            'event' => 'deliver',
        ])->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'order_status' => 'Delivered',
        ]);
    }

    public function test_admin_can_cancel_order_with_note(): void
    {
        $customer = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $order = $this->createOrder($customer, $this->createCart($customer), $paymentMethod, [
            'order_status' => 'Processing',
        ]);

        $response = $this->patch("/admin/orders/{$order->order_id}/event", [
            'event' => 'cancel',
            'cancellation_note' => 'Out of stock',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'order_status' => 'Cancelled',
            'cancellation_note' => 'Out of stock',
        ]);
    }

    public function test_admin_rejects_invalid_transition(): void
    {
        $customer = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $order = $this->createOrder($customer, $this->createCart($customer), $paymentMethod, [
            'order_status' => 'Pending',
        ]);

        $response = $this->patch("/admin/orders/{$order->order_id}/event", [
            'event' => 'ship',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'order_status' => 'Pending',
        ]);
    }

    public function test_admin_can_approve_and_reject_payment(): void
    {
        $customer = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $order = $this->createOrder($customer, $this->createCart($customer), $paymentMethod, [
            'payment_review_status' => 'pending',
        ]);

        $this->patch("/admin/orders/{$order->order_id}/event", [
            'event' => 'approve_payment',
        ])->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'payment_review_status' => 'approved',
        ]);

        Order::whereKey($order->order_id)->update(['payment_review_status' => 'pending']);

        $this->patch("/admin/orders/{$order->order_id}/event", [
            'event' => 'reject_payment',
        ])->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'payment_review_status' => 'rejected',
        ]);
    }

    public function test_admin_updates_delivery_status_when_first_party_enabled(): void
    {
        Notification::fake();

        $customer = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $order = $this->createOrder($customer, $this->createCart($customer), $paymentMethod, [
            'is_first_party_delivery' => true,
            'delivery_status' => 'Preparing',
        ]);

        $this->patch("/admin/orders/{$order->order_id}/event", [
            'event' => 'delivery_out',
        ])->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'delivery_status' => 'Out for Delivery',
        ]);

        Notification::assertSentTo($customer, \App\Notifications\OrderStatusNotification::class);
    }

    public function test_admin_sends_notification_when_delivery_preparing(): void
    {
        Notification::fake();

        $customer = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $order = $this->createOrder($customer, $this->createCart($customer), $paymentMethod, [
            'is_first_party_delivery' => true,
            'delivery_status' => 'N/A',
            'order_status' => 'Processing',
        ]);

        $this->patch("/admin/orders/{$order->order_id}/event", [
            'event' => 'delivery_preparing',
        ])->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'delivery_status' => 'Preparing',
        ]);

        Notification::assertSentTo($customer, \App\Notifications\OrderStatusNotification::class);
    }

    public function test_admin_can_timeout_fail_processing_order(): void
    {
        $customer = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $order = $this->createOrder($customer, $this->createCart($customer), $paymentMethod, [
            'order_status' => 'Processing',
            'order_date' => now()->subDays(15)->toDateString(),
        ]);

        $response = $this->patch("/admin/orders/{$order->order_id}/event", [
            'event' => 'timeout_fail',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'order_status' => 'Failed',
        ]);
    }

    public function test_admin_can_resolve_problem_order(): void
    {
        $customer = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $order = $this->createOrder($customer, $this->createCart($customer), $paymentMethod, [
            'order_status' => 'Cancelled',
        ]);

        $response = $this->patch("/admin/orders/{$order->order_id}/resolve");

        $response->assertStatus(302);
        $this->assertDatabaseMissing('orders', [
            'order_id' => $order->order_id,
            'resolved_at' => null,
        ]);
    }

    public function test_admin_cannot_resolve_non_problem_order(): void
    {
        $customer = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $order = $this->createOrder($customer, $this->createCart($customer), $paymentMethod, [
            'order_status' => 'Processing',
        ]);

        $response = $this->patch("/admin/orders/{$order->order_id}/resolve");

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'resolved_at' => null,
        ]);
    }
}
