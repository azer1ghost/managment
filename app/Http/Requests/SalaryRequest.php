<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'nullable|integer',
            'company_id' => 'nullable|integer',
        ];
    }
}
