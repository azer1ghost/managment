<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'earning' => 'nullable|numeric|between:0,100000',
            'currency' => 'nullable|string',
            'currency_rate' => 'nullable|numeric|between:0,100000',
            'detail' => 'nullable|string',
            'user_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'asan_imza_id' => 'nullable|integer',
            'service_id' => 'nullable|integer',
            'client_id' => 'nullable|integer',
            'parameters' => 'nullable|array',
            'hard_level' => 'nullable|integer',
            'status' => 'nullable|integer',
            'datetime' => 'nullable|date',
            'verified' => 'nullable'
        ];
    }
}
