<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogisticsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'integer|nullable',
            'number' => 'integer|nullable',
            'service_id' => 'integer|nullable',
            'reference_id' => 'integer|nullable',
            'client_id' => 'integer|nullable',
            'transport_type' => 'integer|nullable',
            'status' => 'integer|nullable',
            'parameters' => 'nullable|array',
            'origin_country' => 'nullable|string|max:100',
            'origin_city' => 'nullable|string|max:100',
            'destination_country' => 'nullable|string|max:100',
            'destination_city' => 'nullable|string|max:100',
            'vendor_id' => 'nullable|integer|exists:suppliers,id',
            'payment_status' => 'nullable|in:unpaid,partial,paid',
            'shipping_type' => 'nullable|in:FTL,LTL,LCL,FCL,FTL_avia',
            'incoterms' => 'nullable|string|max:10',
        ];
    }
}