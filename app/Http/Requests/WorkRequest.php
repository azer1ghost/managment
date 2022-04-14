<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'detail' => 'nullable|string',
            'user_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'asan_imza_id' => 'nullable|integer',
            'service_id' => 'nullable|integer',
            'client_id' => 'nullable|integer',
            'parameters' => 'nullable|array',
            'status' => 'nullable|integer',
            'payment_method' => 'nullable|integer',
            'satisfaction' => 'nullable|integer',
            'datetime' => 'nullable|date',
            'verified' => 'nullable',
            'rejected' => 'nullable'
        ];
    }
}
