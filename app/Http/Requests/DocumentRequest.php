<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function rules(): array
    {
        $file = $this->isMethod('POST') ? 'required' : 'nullable';
        $name = $this->isMethod('POST') ? 'nullable' : 'required';

        return [
            'name' => "$name|string",
            'file' => "$file|mimes:jpeg,jpg,png,doc,docx,pdf,xls,xlsx|max:20480",
            'model' => "$file|string",
        ];
    }
}