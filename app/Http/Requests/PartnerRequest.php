<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'note' => 'nullable|string|max:400',
        ];
    }
}
