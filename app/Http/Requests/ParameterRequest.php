<?php

namespace App\Http\Requests;

use App\Models\Parameter;
use Illuminate\Foundation\Http\FormRequest;

class ParameterRequest extends FormRequest
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
            'name'      => 'required|string|max:255',
            'type'      => 'required|string|max:255',
            'companies' => 'array',
            'parameter_id'   => 'integer',
        ];
    }
}
