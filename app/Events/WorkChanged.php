<?php

namespace App\Events;

use App\Models\User;
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
        if ($work->getAttribute('user_id') !== null) {
            $user = $work->getRelationValue('user');
        }else
            $user = User::where('user_id', 26)->first()->get();

        $this->url = route('works.show', $work);
        $this->creator = $user;
        $this->title = 'İşin Gb Sayı Dəyişdirilib';
        $this->receivers = User::where('department_id', 5)->get()->all();
        $this->body = 'Bu geri qaytarılan işin bəyannamə sayı və ya kod sayı dəyişib';
    }

}
