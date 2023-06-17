<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{

    public function rules()
    {
        return [
            'company_id' => 'nullable|integer',
            'customCompany' => 'nullable|string',
            'name' => 'required|string',
            'amount' => 'nullable|string',
            'currency' => 'nullable|string',
        ];
    }
}
