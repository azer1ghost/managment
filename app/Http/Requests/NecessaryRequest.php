<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NecessaryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'detail' => 'nullable|string',
            ];
    }
}
