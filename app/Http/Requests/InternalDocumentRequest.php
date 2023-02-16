<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InternalDocumentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'department_id' => 'nullable',
            'document_name' => 'nullable|string',
            'document_code' => 'nullable|string',
            ];
    }
}
