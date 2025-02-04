<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RuleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'content' => 'nullable|string',
        ];
    }
}
