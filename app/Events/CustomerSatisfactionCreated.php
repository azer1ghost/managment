<?php

namespace App\Events;

use App\Models\CustomerSatisfaction;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class CustomerSatisfactionCreated
{
    use Dispatchable;

    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct(CustomerSatisfaction $customerSatisfaction)
    {
        $quality_control_user = User::where('department_id', 25)->get()->all();
        $this->url = route('customer-satisfactions.index');
        $this->creator = $quality_control_user;
        $this->title = 'Müştəri Məmnuniyyəti';
        $this->body = 'Müştəri məmnuniyyətini bildirib';
        $this->receivers = $quality_control_user;
    }
}