<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{


    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'file' => 'nullable|max:2048'
        ];
    }
}
