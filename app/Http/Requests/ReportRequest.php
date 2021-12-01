<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'detail' => 'nullable|string',
            'date' => 'nullable|date:Y-m-d'
        ];
    }
}
