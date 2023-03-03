<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogisticsRequest extends FormRequest
{
    public function rules()
    {
        return [
//            'reg_number' => 'nullable|string',
            'user_id' => 'integer|nullable',
            'service_id' => 'integer|nullable',
            'logistics_client_id' => 'integer|nullable',
            'currency' => 'string|nullable',
            'status' => 'integer|nullable',
            'datetime' => 'date|nullable',
            'paid_at' => 'date|nullable',
            'parameters' => 'nullable|array',
        ];
    }
}