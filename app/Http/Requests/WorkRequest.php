<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'nullable|string',
            'detail' => 'nullable|string',
            'user_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'asan_imza_id' => 'nullable|integer',
            'service_id' => 'nullable|integer',
            'client_id' => 'nullable|integer',
            'custom_asan' => 'nullable|string',
            'custom_client' => 'nullable|string',
            'parameters' => 'nullable|array',
            'status' => 'nullable|integer',
            'payment_method' => 'nullable|integer',
            'datetime' => 'nullable|date',
            'verified' => 'nullable',
            'rejected' => 'nullable',
            'paid_check' => 'nullable',
            'vat_paid' => 'nullable',
            'bank_charge' => 'nullable'
        ];
    }
}
