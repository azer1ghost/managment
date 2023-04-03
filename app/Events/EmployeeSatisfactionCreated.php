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
        $this->receivers[] = $employeeSatisfaction->getRelationValue('employees');

//        if (is_numeric($employeeSatisfaction->getAttribute('user_id'))){
//            $this->receivers[] = $employeeSatisfaction->getRelationValue('user');
//            $this->body = trans('translates.works.content.user');
//        }else{
//            $this->receivers = $employeeSatisfaction->getRelationValue('departments')->users()
////                ->whereNotIn('id', [auth()->id()])
//                ->get()->all();
//            $this->body = trans('translates.works.content.department');
//        }
    }
}