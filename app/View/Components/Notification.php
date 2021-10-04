<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends Component
{
    public array $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
        DatabaseNotification::find($notification['id'])->markAsRead();
    }

    public function render()
    {
        return view('components.notification');
    }
}
