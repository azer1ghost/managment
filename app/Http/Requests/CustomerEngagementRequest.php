<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerEngagementRequest extends FormRequest
{

    public function rules()
    {
        return [
            'user_id'=>'required|integer',
            'company_id'=>'required|integer',
        ];
    }
}
