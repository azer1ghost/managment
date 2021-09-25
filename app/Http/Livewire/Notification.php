<?php

namespace App\Http\Livewire;
use Livewire\Component;

class Notification extends Component
{
//    public  $notifications = null;

//    public function mount($notifications)
//    {
//        $this->notifications = $notifications;
//    }

    public function newNotifications()
    {
        $this->emit('newNotifications');
    }

    public function render()
    {
        return view('livewire.notification');
    }
}
