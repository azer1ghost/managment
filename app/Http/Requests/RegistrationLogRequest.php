<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationLogRequest extends FormRequest
{
    public function rules()
    {
        return [
            'performer' => 'nullable|integer',
            'receiver' => 'nullable|integer',
            'sender' => 'nullable|string',
            'number' => 'nullable|string',
            'description' => 'nullable|string',
            'arrived_at' => 'nullable|date',
            'received_at' => 'nullable|date',
            ];
    }
}
