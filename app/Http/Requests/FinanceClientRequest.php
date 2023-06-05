<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinanceClientRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'voen' => 'nullable|string',
            'hn' => 'nullable|string',
            'mh' => 'nullable|string',
            'code' => 'nullable|string',
            'bank' => 'nullable|string',
            'bvoen' => 'nullable|string',
            'swift' => 'nullable|string',
            'orderer' => 'nullable|string',
        ];
    }
}
