<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpeg,jpg,png,doc,docx,pdf,xls,xlsx|max:2048',
            'model'  => 'nullable|string',
        ];
    }
}