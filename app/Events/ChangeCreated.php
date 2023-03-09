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
        $this->url = route('changes.show', $change);
        $this->creator = $change->getRelationValue('users');
        $this->title = trans('translates.changes.title');
        $this->body = $change->getAttribute('description');
        $this->receivers = User::isActive()->get()->all();
    }
}