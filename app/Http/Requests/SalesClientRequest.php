<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesClientRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'=>'required|string',
            'phone'=>'nullable|string',
            'voen'=>'nullable|string',
            'detail'=>'nullable|string',
            'close' => 'nullable'
        ];
    }
}
