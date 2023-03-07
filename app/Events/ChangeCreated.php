<?php

namespace App\Events;

use App\Models\Change;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class ChangeCreated
{
    use Dispatchable;

    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct(Change $change)
    {
        $this->url = route('registration-logs.index');
        $this->creator = $change->getRelationValue('users');
        $this->title = trans('translates.registration_logs.title');
        $this->body = trans('translates.registration_logs.content');
        $this->receivers = $change->getRelationValue('departments')->users()->get()->all();
    }
}