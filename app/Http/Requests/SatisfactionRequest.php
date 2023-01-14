<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SatisfactionRequest extends FormRequest
{

    public function rules()
    {
        return [
            'company_id' => 'nullable',
            'parameters' => 'nullable|array',
        ];
    }
}
