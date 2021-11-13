<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'detail' => 'nullable|string|nullable',
            'company_id' => 'nullable|integer|nullable',
            'department_id' => 'nullable|integer|nullable',
            'translate' => 'nullable|array',
            ];
    }
}
