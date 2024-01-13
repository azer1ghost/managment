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
        $this->creator = User::isQualityControl()->get()->all();
        $this->title = 'Müştəri Məmnuniyyəti';
        $this->body = 'Müştəri məmnuniyyətini bildirib';
        $this->receivers = User::isQualityControl()->get()->all();
    }
}