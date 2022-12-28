<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'nullable',
            'department_id' => 'nullable',
            'description' => 'nullable|string',
            'reason' => 'nullable|string',
            'result' => 'nullable|string',
            'responsible' => 'nullable|integer',
            'effectivity' => 'nullable|integer',
            'note' => "nullable|string|max:5000",
            'datetime' => 'nullable|date',
            ];
    }
}
