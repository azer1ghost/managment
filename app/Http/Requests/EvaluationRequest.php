<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'supplier_id'  => 'required|integer',
            'quality'      => 'required|integer',
            'delivery'     => 'required|integer',
            'distributor'  => 'required|integer',
            'availability' => 'required|integer',
            'certificate'  => 'required|integer',
            'support'      => 'required|integer',
            'price'        => 'required|integer',
            'payment'      => 'required|integer',
            'returning'    => 'required|integer',
            'replacement'  => 'required|integer',
        ];
    }
}
