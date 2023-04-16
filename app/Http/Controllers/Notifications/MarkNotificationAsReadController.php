<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;

class MarkNotificationAsReadController extends Controller
{
    public function __invoke(string $notificationId)
    {
        $notification = auth()->user()->notifications()->where('id', $notificationId)->firstOrFail();

        $notification->markAsRead();
    }
}
