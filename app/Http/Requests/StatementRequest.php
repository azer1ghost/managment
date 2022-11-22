<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatementRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'string|nullable',
            'body' => 'string|nullable',
            'attribute' => 'string|nullable',
        ];
    }
}
