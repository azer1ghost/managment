<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkStatusRejected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct($work)
    {
        $this->url = route('works.show', $work);
        $this->creator = $work->getRelationValue('creator');
        $this->title = trans('translates.works.statuses.rejected');
        $this->receivers[] = $work->getRelationValue('user');
        $this->body = $work->getRelationValue('service')->getAttribute('name');
    }
}
