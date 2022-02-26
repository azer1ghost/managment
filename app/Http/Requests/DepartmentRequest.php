<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => 'nullable',
            'name' => 'nullable|array',
            'short_name' => 'nullable|array',
            'perms' => 'nullable|array',
            'all_perms' => 'nullable|string',
        ];
    }
}
