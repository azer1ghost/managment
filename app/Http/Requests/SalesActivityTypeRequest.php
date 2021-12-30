<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesActivityTypeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'hard_columns' => 'required|array',
        ];
    }
}
