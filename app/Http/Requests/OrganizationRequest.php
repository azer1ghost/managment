<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'translate' => 'nullable',
            'is_certificate' => 'nullable',
        ];
    }
}
