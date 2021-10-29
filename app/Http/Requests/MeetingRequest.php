<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|nullable',
            'status' => 'required|integer|nullable',
            'datetime' => 'required|date|nullable',
        ];
    }
}
