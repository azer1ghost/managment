<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|nullable',
            'detail' => 'required|string|nullable',
            'user_id' => 'required|integer|nullable',
            'company_id' => 'required|integer|nullable',
            'department_id' => 'required|integer|nullable',
        ];
    }
}
