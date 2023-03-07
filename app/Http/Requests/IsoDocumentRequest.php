<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IsoDocumentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            ];
    }
}
