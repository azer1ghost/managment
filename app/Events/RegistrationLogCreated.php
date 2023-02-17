<?php

namespace App\Events;

use App\Models\RegistrationLog;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class RegistrationLogCreated
{
    use Dispatchable;

    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct(RegistrationLog $registrationLog)
    {
        $this->url = route('registration-logs.index');
        $this->creator = $registrationLog->getRelationValue('performers');
        $this->title = trans('translates.registration_logs.title');
        $this->body = trans('translates.registration_logs.content');
        $this->receivers[] = $registrationLog->getRelationValue('receivers');
    }
}