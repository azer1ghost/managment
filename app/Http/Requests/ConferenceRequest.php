<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConferenceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'string',
            'status' => 'integer',
            'datetime' => 'date',
        ];
    }
}
