<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobInstructionRequest extends FormRequest
{

    public function rules()
    {
        return [
            'user_id' => 'required',
            'instruction' => 'required'
        ];
    }
}
