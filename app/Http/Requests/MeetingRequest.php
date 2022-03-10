<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|nullable',
            'status' => 'int|nullable',
            'department_id' => 'int|nullable',
            'will_start_at' => 'date',
            'will_end_at' => 'date',
        ];
    }
}
