<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesActivityTypeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'hard_columns' => 'nullable|array',
            'name' => 'nullable|array',
            'description' => 'nullable|array',
        ];
    }
}
