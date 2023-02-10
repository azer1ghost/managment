<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerSatisfactionRequest extends FormRequest
{

    public function rules()
    {
        return [
            'satisfaction_id' => 'required|integer',
            'parameters'      => 'nullable|array',
            'rate'            => 'required|integer',
            'price_rate'      => 'required|integer',
            'note'            => 'nullable|string',
            'detail'          => 'nullable|string'
        ];
    }
}
