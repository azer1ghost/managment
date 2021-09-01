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
            'key'   => 'required|string|max:255',
            'name'  => 'string|nullable',
            'icon'  => 'string|nullable',
            'color' => 'string|nullable',
            'bg_color' => 'string|nullable',
            'detail' => 'string|nullable',
            'html' => 'string|nullable',
            'query' => 'string|nullable',
            'order' => 'integer',
        ];
    }
}
