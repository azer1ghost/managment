<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkReturned
{
    use Dispatchable, SerializesModels;
    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct($work)
    {
        $this->url = route('works.show', $work);
        $this->creator = $work->getRelationValue('user');
        $this->title = 'İş geri qaytarıldı';
        $this->receivers = User::where('department_id', 22)->get()->all();
        $this->body = 'İşin statusu geri qaytarıldı olaraq dəyişdirilib';
    }

}
