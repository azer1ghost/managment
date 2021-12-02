<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'daterange' => 'required|string',
            'type' => 'required|integer',
            'is_day_off' => 'nullable',
            'is_repeatable' => 'nullable',
        ];
    }
}
