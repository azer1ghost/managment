<?php

namespace App\Http\Requests;

use App\Models\Parameter;
use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'role_id'        => 'integer',
            'department_id'  => 'integer',
            'perms' => 'nullable|array',
            'all_perms' => 'nullable|string',
            'order' => 'nullable|integer',
            'name' => 'nullable|array'
        ];
    }
}
