<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeSatisfactionRequest extends FormRequest
{

    public function rules()
    {
        return [
            'type' => 'integer|nullable',
            'department_id' => 'nullable|string',
            'employee' => 'nullable|string',
            'activity' => 'nullable|string',
            'content' => 'nullable|string',
            'reason' => 'nullable|string',
            'result' => 'nullable|string',
            'is_enough' => 'nullable|boolean',
            'more_time' => 'nullable|boolean',
            'deadline' => 'nullable|date',
            'status' => 'nullable|integer',
            'effectivity' => 'nullable|integer',
            'note' => 'nullable|string',
        ];
    }
}
