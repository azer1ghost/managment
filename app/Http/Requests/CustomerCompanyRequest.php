<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerCompanyRequest extends FormRequest
{
    public function rules()
    {
        return [
           'name' => 'string',
           'email' => 'email',
           'number' => 'string',
           'voen' => 'string',
           'address' => 'string',
        ];
    }
}
