<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('manage-user', User::class);
    }

    public function rules(): array
    {
        // $logo = $this->isMethod('POST') ? 'required' : 'nullable';
        return [
            'name'      => 'required|string|max:255',
            'surname'      => 'required|string|max:255',
            'father'      => 'required|string|max:255',
            'serial'      => 'required|string|max:255',
            'fin'      => 'required|string|max:255',
            'birthday'      => 'required|date',
            'email'      => 'required|email:rfc,dns',
            'email_coop'      => 'required|email:rfc,dns',
            'position'      => 'required|string|max:255',
            'department'      => 'required|string|max:255',
            'phone'     => 'required|string|max:255',
            'phone_coop'     => 'required|string|max:255',
            'country'   => 'required|string|max:255',
            'city'   => 'required|string|max:255',
            'address'   => 'required|string|max:255',
            'company_id'   => 'required|string|max:255',
            'role_id'   => 'required|string|max:255',
        ];
    }

}
