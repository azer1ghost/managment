<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InternalRelationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'nullable',
            'department_id' => 'nullable',
            'case' => 'nullable',
            'applicant' => 'nullable|string',
            'reciever' => 'nullable|string',
            'tool' => 'nullable|string',
            'contact_time' => 'nullable|string',
            ];
    }
}
