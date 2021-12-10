<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'users' => 'array',
            'class' => 'string',
            'title' => 'string',
            'detail' => 'string',
            'repeat_rate' => 'string',
            'permissions' => 'string',
            'status' => 'nullable',
            'will_notify_at' => 'date',
            'will_end_at' => 'date',
            'perms' => 'nullable|array',
            'all_perms' => 'nullable|string',
        ];
    }
}
