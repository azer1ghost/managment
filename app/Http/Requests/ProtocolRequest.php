<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProtocolRequest extends FormRequest
{
    public function rules()
    {
        return [
            'performer' => 'nullable|integer',
            'signature' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'content' => 'nullable|string',
            'protocol_no' => 'nullable|string',
            'date' => 'nullable|date',
            ];
    }
}
