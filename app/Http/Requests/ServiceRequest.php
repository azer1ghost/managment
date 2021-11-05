<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'detail' => 'required|string|nullable',
            'company_id' => 'required|integer|nullable',
            'department_id' => 'required|integer|nullable',
            'translate' => 'nullable|array',
            ];
    }
}
