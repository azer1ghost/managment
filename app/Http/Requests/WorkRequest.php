<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'detail' => 'nullable|string',
            'user_id' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'service_id' => 'nullable|integer',
            'client_id' => 'nullable|integer'
        ];
    }
}
