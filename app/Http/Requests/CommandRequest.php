<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommandRequest extends FormRequest
{
    public function rules()
    {
        return [
            'executor' => 'nullable|integer',
            'confirming' => 'nullable|integer',
            'number' => 'nullable|string',
            'content' => 'nullable|string',
            'command_date' => 'nullable|date',
            'company_id' => 'nullable|integer',
            ];
    }
}
