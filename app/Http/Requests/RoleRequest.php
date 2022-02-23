<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'key'   => 'required|string',
            'perms' => 'nullable|array',
            'all_perms' => 'nullable|string',
            'name' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'perms.required_without' => 'At least one permission field is required when All permissions is not present.'
        ];
    }
}
