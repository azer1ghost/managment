<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'integer|nullable',
            'content' => 'string|nullable',
        ];
    }
}
