<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsanImzaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id'=>'required|integer',
            'company_id'=>'required|integer',
            'phone'=>'nullable|string',
            'asan_id'=>'nullable|string',
        ];
    }
}
