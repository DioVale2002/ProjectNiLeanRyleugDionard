<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $customer = Auth::guard('customer')->user();

        $notifications = $customer->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'read_at' => $notification->read_at,
                    'data' => $notification->data,
                ];
            });

        return response()->json([
            'unread_count' => $customer->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markRead(string $notificationId): JsonResponse
    {
        $customer = Auth::guard('customer')->user();

        $notification = $customer->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json(['status' => 'ok']);
    }

    public function markAllRead(): JsonResponse
    {
        $customer = Auth::guard('customer')->user();

        $customer->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['status' => 'ok']);
    }
}
