<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesActivityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'datetime' => 'nullable|date',
            'address' => 'nullable|string',
            'activity_area' => 'nullable|string',
            'client_name' => 'nullable|string',
            'result' => 'nullable|string',
            'organization_id' => 'nullable|integer',
            'certificate_id' => 'nullable|integer',
            'sales_activity_type_id' => 'nullable|integer',
            'supplies' => 'nullable|array'
        ];
    }
}
