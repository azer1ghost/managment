<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarcodeRequest extends FormRequest
{

    public function rules()
    {
        return [
            'subject'   => "filled|int|max:255",
            'source'    => "filled|int|max:255",
            'note'      => "nullable|string|max:5000",
            'code'      => "nullable|string",
            'status'    => "filled|int",
            'client_id' => 'nullable|integer',
            'mediator_id' => 'nullable|integer',
        ];
    }

}
