<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'department_id' => 'required|integer',
            'message' => 'required|string',
        ];
    }
}
