<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\SystemAlert;
use Illuminate\Support\Facades\Notification;

class AlertService
{
    /**
     * Send system alert to all admins.
     */
    public function send(string $title, string $message, string $type = 'info')
    {
        $admins = User::all(); // In real app, filter by role/permission
        Notification::send($admins, new SystemAlert($title, $message, $type));
    }
}
