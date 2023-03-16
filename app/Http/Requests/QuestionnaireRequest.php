<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionnaireRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'client_id' => 'required|string|nullable',
            'source' => 'array|nullable',
            'customs' => 'array|nullable',
            'send_email' => 'nullable',
            'novelty_us' => 'string|nullable',
            'novelty_customs' => 'string|nullable',
        ];
    }
}
