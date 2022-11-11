<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InternalNumberRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'phone'=> 'nullable|string',
            'detail' => 'nullable|string',
            'user_id' => 'nullable',
        ];
    }
}
