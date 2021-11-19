<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'detail' => 'nullable|string',
            'icon' => 'nullable|string',
            'company_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'service_id' => 'nullable|integer',
            'translate' => 'nullable|array',
            'parameters' => 'nullable|array'
        ];
    }
}
