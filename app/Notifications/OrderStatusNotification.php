<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Order $order,
        private readonly string $title,
        private readonly string $body,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting('Hello ' . ($notifiable->first_name ?? 'Customer') . ',')
            ->line($this->body)
            ->line('Order #' . str_pad((string) $this->order->order_id, 8, '0', STR_PAD_LEFT))
            ->action('View Orders', route('account.orders'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'order_id' => $this->order->order_id,
            'status' => $this->order->order_status,
            'view_url' => route('account.orders'),
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
