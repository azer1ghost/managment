<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SummitRequest extends FormRequest
{
    public function rules()
    {
        return [
            'club'         => 'nullable|string',
            'event'        => 'nullable|string',
            'format'       => 'nullable|string',
            'place'        => 'nullable|string',
            'dresscode'    => 'nullable|string',
            'status'       => 'nullable|integer',
            'date'         => 'nullable|date',
            ];
    }
}