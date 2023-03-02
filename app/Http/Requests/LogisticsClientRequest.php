<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogisticsClientRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name'   => "nullable|string",
            'phone'    => "nullable|string",
            'email'      => "nullable|string",
            'voen'      => "nullable|string",
        ];
    }

}
