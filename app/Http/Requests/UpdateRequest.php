<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'content' => 'nullable|string',
            'status' => 'nullable|integer',
            'parent_id' => 'nullable|integer',
            'datetime' => 'nullable|date',
            'done_at' => 'nullable|date',
        ];
    }
}
