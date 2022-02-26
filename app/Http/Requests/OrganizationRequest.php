<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|array',
            'detail' => 'nullable|array',
            'is_certificate' => 'nullable',
        ];
    }
}
