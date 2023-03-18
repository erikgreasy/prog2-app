<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarkNotificationAsReadController extends Controller
{
    public function __invoke(string $notificationId)
    {
        $notification = auth()->user()->notifications()->where('id', $notificationId)->firstOrFail();

        $notification->markAsRead();
    }
}
