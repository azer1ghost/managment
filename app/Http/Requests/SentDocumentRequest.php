<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SentDocumentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'overhead_num' => 'nullable|string',
            'organization' => 'nullable|string',
            'content' => 'nullable|string',
            'note' => 'nullable|string',
            'sent_date' => 'nullable|date',
        ];
    }
}
