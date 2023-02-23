<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessRateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'position_id' => 'nullable|integer',
            'folder_id' => 'nullable|integer',
            'composition' => 'nullable|string',
            ];
    }
}
