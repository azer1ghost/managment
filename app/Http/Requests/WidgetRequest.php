<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WidgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key'  => 'required|string|max:255',
            'class_attribute'  => 'nullable|string|max:255',
            'style_attribute' => 'nullable|string|nullable',
            'icon' => 'nullable|string',
            'order' => 'required|integer',
            'status' => 'nullable',
            'translate' => 'nullable|array',

        ];
    }
}
