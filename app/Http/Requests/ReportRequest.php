<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'detail' => 'required|string',
            'date' => 'required|date:Y-m-d'
        ];
    }
}
