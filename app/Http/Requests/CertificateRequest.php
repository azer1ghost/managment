<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
{

    public function rules()
    {
        return [
            'organization_id' => 'nullable',
            'name' => 'nullable|array',
            'detail' => 'nullable|array',
        ];
    }
}
