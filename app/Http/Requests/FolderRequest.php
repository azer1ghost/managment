<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FolderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'company_id' => 'nullable|integer',
            ];
    }
}
