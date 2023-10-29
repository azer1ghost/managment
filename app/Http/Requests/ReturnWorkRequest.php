<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnWorkRequest extends FormRequest
{
    public function rules()
    {
        return [
            'work_id' => 'required|integer',
            'return_reason' => 'required|string',
            'main_reason' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string',
            'note' => 'nullable|string',
        ];
    }
}
