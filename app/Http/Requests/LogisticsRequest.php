<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogisticsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'integer|nullable',
            'service_id' => 'integer|nullable',
            'reference_id' => 'integer|nullable',
            'client_id' => 'integer|nullable',
            'transport_type' => 'integer|nullable',
            'status' => 'integer|nullable',
            'parameters' => 'nullable|array',
        ];
    }
}