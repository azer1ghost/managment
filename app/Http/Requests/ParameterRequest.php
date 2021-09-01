<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParameterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'type'      => 'required|string|max:255',
            'label'      => 'required|string|max:255',
            'placeholder'      => 'required|string|max:255',
            'order' => 'required|integer',
            'companies' => 'array',
            'options' => 'array',
            'option_id'   => 'nullable',
        ];
    }
}
