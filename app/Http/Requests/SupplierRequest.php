<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'         => 'required|string',
            'voen'         => 'nullable|string|max:15',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:50',
            'note'         => 'nullable|string|max:400',
            'supplier_id'  => 'nullable|integer',
            'quality'      => 'nullable|integer',
            'delivery'     => 'nullable|integer',
            'distributor'  => 'nullable|integer',
            'availability' => 'nullable|integer',
            'certificate'  => 'nullable|integer',
            'support'      => 'nullable|integer',
            'price'        => 'nullable|integer',
            'payment'      => 'nullable|integer',
            'returning'    => 'nullable|integer',
            'replacement'  => 'nullable|integer',
        ];
    }
}
