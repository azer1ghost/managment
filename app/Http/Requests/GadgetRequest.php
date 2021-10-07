<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GadgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key'  => 'required|string|max:255',
            'type'  => 'required|string|max:255',
            'name' => 'required|string|nullable',
            'labels' => 'nullable|string',
            'colors' => 'nullable|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'bg_color' => 'nullable|string',
            'details' => 'nullable|string',
            'query' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'nullable'
        ];
    }
}
