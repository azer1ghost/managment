<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'nullable|string',
            'detail' => 'nullable|string',
            'icon' => 'nullable|string',
            'company_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'service_id' => 'nullable|integer',
            'name' => 'nullable|array',
            'has_asan_imza' => 'nullable',
            'parameters' => 'nullable|array',
            'ordering' => 'nullable|integer',

        ];
    }
}
