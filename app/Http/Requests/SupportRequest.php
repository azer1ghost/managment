<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'detail' => 'nullable|string',
            ];
    }
}
