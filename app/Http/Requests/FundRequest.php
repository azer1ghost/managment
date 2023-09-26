<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FundRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'voen' => 'nullable|integer',
            'main_activity' => 'nullable|string',
            'asan_imza' => 'nullable|string',
            'code' => 'nullable|string',
            'adress' => 'nullable|string',
            'voen_code' => 'nullable|string',
            'voen_pass' => 'nullable|string',
            'pass' => 'nullable|string',
            'respublika_code' => 'nullable|string',
            'respublika_pass' => 'nullable|string',
            'kapital_code' => 'nullable|string',
            'kapital_pass' => 'nullable|string',

        ];
    }
}
