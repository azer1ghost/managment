<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{

    public function rules()
    {
        return [
            'code' => 'nullable|string',
            'service' => 'nullable|string',
            'user_id' => 'nullable|integer',
            'amount' => 'nullable|string',
            'status' => 'nullable|integer',
            'note' => 'nullable|string'
        ];
    }
}
