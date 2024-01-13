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
        $this->url = route('customer-satisfactions.index');
        $this->creator = User::find(123);
        $this->title = 'Müştəri Məmnuniyyəti';
        $this->body = 'Müştəri məmnuniyyətini bildirib';
        $this->receivers = User::where('department_id', 25)->get()->all();
    }
}