<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerSatisfactionRequest extends FormRequest
{

    public function rules()
    {
        return [
            'company_id' => 'nullable'
        ];
    }
}
