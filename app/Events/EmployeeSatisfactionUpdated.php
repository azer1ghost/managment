<?php

namespace App\Events;

use App\Models\EmployeeSatisfaction;
use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;

class EmployeeSatisfactionUpdated
{
    use Dispatchable;

    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct(EmployeeSatisfaction $employeeSatisfaction)
    {
        $this->url = route('employee-satisfaction.show', $employeeSatisfaction);
        $this->creator = $employeeSatisfaction->getAttribute('users');
        $this->body = $employeeSatisfaction->getAttribute('note');
        $this->title = trans('translates.employee_satisfactions.incompatibility', [
            'types' => trans('translates.employee_satisfactions.types.' . $employeeSatisfaction->getAttribute('type'))
        ]);

        $receiversIds = $employeeSatisfaction->getAttribute('users');
        $this->receivers = User::whereIn('id', $receiversIds)->get()->toArray();
    }
}