<?php

namespace App\Livewire\Components;

use Livewire\Component;

class HeaderNotification extends Component
{
    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.components.header-notification', [
            'notifications' => auth()->user()?->unreadNotifications()->limit(5)->get() ?? collect([]),
            'count' => auth()->user()?->unreadNotifications()->count() ?? 0
        ]);
    }
}
