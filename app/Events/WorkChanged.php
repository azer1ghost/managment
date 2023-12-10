<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkChanged
{
    use Dispatchable, SerializesModels;
    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct($work)
    {
        $this->url = route('works.show', $work);
        $this->creator = $work->getRelationValue('user');
        $this->title = 'İşin Gb Sayı Dəyişdirilib';
        $this->receivers = User::hasPermission('viewAny-financeClient')->get()->all();
        $this->body = 'Bu geri qaytarılan işin bəyannamə sayı dəyişib';
    }

}
