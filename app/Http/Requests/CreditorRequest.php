<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditorRequest extends FormRequest
{

    public function rules()
    {
        return [
            'supplier_id' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'overhead' => 'nullable|string',
            'creditor' => 'nullable|string',
            'amount' => 'nullable|string',
            'vat' => 'nullable|string',
            'status' => 'nullable|string',
            'note' => 'nullable|string',
            'paid_at' => 'nullable|date',
            'overhead_at' => 'nullable|date',
            'last_date' => 'nullable|date',
        ];
    }
}
