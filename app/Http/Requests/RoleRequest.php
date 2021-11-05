<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key'   => 'required|string',
            'perms' => 'required_without:all_perms|array',
            'all_perms' => 'nullable|string',
            'translate' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'perms.required_without' => 'At least one permission field is required when All permissions is not present.'
        ];
    }
}
