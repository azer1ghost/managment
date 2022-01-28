<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerEngagementRequest extends FormRequest
{

    public function rules()
    {
        return [
            'client_id'=>'required|integer',
            'user_id'=>'nullable|integer',
            'partner_id'=>'nullable|integer',
        ];
    }
}
