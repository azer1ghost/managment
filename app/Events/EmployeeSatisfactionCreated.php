<?php

namespace App\Events;

use App\Models\EmployeeSatisfaction;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class EmployeeSatisfactionCreated
{
    use Dispatchable;

    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct(EmployeeSatisfaction $employeeSatisfaction)
    {
        $this->url = route('employee-satisfaction.show', $employeeSatisfaction);
        $this->creator = $employeeSatisfaction->getRelationValue('users');
        $this->title = trans('translates.employee_satisfactions.incompatibility');
        $this->body = $employeeSatisfaction->getAttribute('content');

        $directors = User::where('role_id', User::DIRECTOR)->all();

        if ($employeeSatisfaction->getAttribute('department_id') == null){
            if($employeeSatisfaction->getAttribute('user_id') !== null){
               $this->receivers = $employeeSatisfaction->getRelationValue('employees');
           }
        }
        else{
            $this->receivers = $employeeSatisfaction->getRelationValue('departments')->users()
//                ->whereNotIn('id', [auth()->id()])
                ->get()->all();
        }
    }
}