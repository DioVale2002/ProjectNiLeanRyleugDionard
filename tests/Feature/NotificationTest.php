<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function createCustomer(): Customer
    {
        return Customer::create([
            'first_name'  => 'Jane',
            'last_name'   => 'Doe',
            'contact_num' => '09123456789',
            'email'       => 'jane' . uniqid() . '@example.com',
            'password'    => bcrypt('password123'),
        ]);
    }

    protected function addNotification(Customer $customer, array $data, ?string $readAt = null): string
    {
        $notification = $customer->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\OrderStatusNotification',
            'data' => $data,
            'read_at' => $readAt,
        ]);

        return (string) $notification->id;
    }

    public function test_customer_can_fetch_notifications(): void
    {
        $customer = $this->createCustomer();
        $this->addNotification($customer, [
            'title' => 'Order Update',
            'body' => 'Your order is now Processing.',
        ]);
        $this->addNotification($customer, [
            'title' => 'Order Update',
            'body' => 'Your order is now Shipped.',
        ], now()->toDateTimeString());

        $this->actingAs($customer, 'customer');

        $response = $this->getJson('/notifications');

        $response->assertOk();
        $response->assertJsonFragment(['unread_count' => 1]);
        $response->assertJsonCount(2, 'notifications');
    }

    public function test_customer_can_mark_notification_read(): void
    {
        $customer = $this->createCustomer();
        $notificationId = $this->addNotification($customer, [
            'title' => 'Order Update',
            'body' => 'Your order is now Processing.',
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->postJson("/notifications/{$notificationId}/read");

        $response->assertOk();
        $this->assertDatabaseMissing('notifications', [
            'id' => $notificationId,
            'read_at' => null,
        ]);
    }

    public function test_customer_can_mark_all_notifications_read(): void
    {
        $customer = $this->createCustomer();
        $this->addNotification($customer, [
            'title' => 'Order Update',
            'body' => 'Your order is now Processing.',
        ]);
        $this->addNotification($customer, [
            'title' => 'Order Update',
            'body' => 'Your order is now Shipped.',
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->postJson('/notifications/read-all');

        $response->assertOk();
        $this->assertSame(0, $customer->fresh()->unreadNotifications()->count());
    }
}
