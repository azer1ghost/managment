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
        $accountants = User::where('department_id', 22)->get()->all();
        $quality_controls = User::where('department_id', 25)->get()->all();
        $chiefs = User::isDepartmentChief()->get()->all();

        $this->url = route('works.show', $work);
        $this->creator = $work->getRelationValue('user');
        $this->title = 'İş geri qaytarıldı';
        $this->receivers = array_merge($accountants, $chiefs, $quality_controls);
        $this->body = 'İşin statusu geri qaytarıldı olaraq dəyişdirilib';
    }
}
