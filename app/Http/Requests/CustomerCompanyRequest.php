<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerCompanyRequest extends FormRequest
{
    public function rules()
    {
        return [
           'name' => 'required|string',
           'email' => 'nullable|email',
           'number' => 'nullable|string',
           'voen' => 'nullable|string',
           'address' => 'nullable|string',
        ];
    }
}
