<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationHandler extends Component
{
    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function getListeners()
    {
        return [
            "echo-private:notifications.".Auth::id().",NotificationEvent" => 'notificationReceived'
        ];
    }

    public function notificationReceived()
    {
        $this->emit('notificationUpdated');
    }

    public function render()
    {
        return view('livewire.notification-handler');
    }
}