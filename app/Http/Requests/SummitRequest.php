<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SummitRequest extends FormRequest
{
    public function rules()
    {
        return [
            'club_name'    => 'nullable|integer',
            'event'        => 'nullable|string',
            'format'       => 'nullable|integer',
            'place'        => 'nullable|string',
            'dresscode'    => 'nullable|string',
            'status'       => 'nullable|integer',
            'date'         => 'nullable|date',
            ];
    }
}
