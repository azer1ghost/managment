<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FolderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'company_id' => 'required|integer',
            'composition' => 'required|string',
        ];
    }
}
