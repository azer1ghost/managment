<?php

namespace App\Http\Requests;

use App\Models\Parameter;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'inquiry_id' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'nullable|string|max:30',
            'priority' => 'required|string|max:30',
            'task_dates' => 'nullable|string',
            'department' => 'required|integer',
            'user' => 'nullable|integer',
            'note' => 'nullable|string',
        ];
    }
}
