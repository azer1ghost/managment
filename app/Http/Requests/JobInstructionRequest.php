<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobInstructionRequest extends FormRequest
{

    public function rules()
    {
        return [
            'user_id' => 'required',
            'department_id' => 'nullable',
            'instruction' => 'required',
            'ordering' => 'nullable|integer',
        ];
    }
}
