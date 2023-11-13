<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryReportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'working_days' => 'nullable|string',
            'actual_days' => 'nullable|string',
            'salary' => 'nullable|string',
            'prize' => 'nullable|string',
            'occupation' => 'nullable|string',
            'advance' => 'nullable|string',
            'date' => 'nullable|date',
            'note' => 'nullable|string'
        ];
    }
}
