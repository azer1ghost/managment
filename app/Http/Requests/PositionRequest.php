<?php

namespace App\Http\Requests;

use App\Models\Parameter;
use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
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
            'name'           => 'required|string|max:255',
            'role_id'        => 'integer',
            'department_id'  => 'integer',
            'perms' => 'array',
            'all_perms' => 'nullable|string',
            'order' => 'nullable|integer'
        ];
    }
}
