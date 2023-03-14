<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessRateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'folder_id' => 'required|integer',
        ];
    }
}
