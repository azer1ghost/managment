<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $password = $this->isMethod('POST') && !$this->routeIs('account.save') ? 'required' : 'nullable';
        return [
            'name'           => 'required|string|max:255',
            'surname'        => 'nullable|string|max:255',
            'avatar'         => 'nullable|string|max:255',
            'father'         => 'nullable|string|max:255',
            'gender'         => 'nullable|string|max:255',
            'serial'         => 'nullable|string|max:255',
            'serial_pattern' => 'nullable|string|max:255',
            'fin'            => 'nullable|string|max:255',
            'email'          => 'required|email:rfc,dns',
            'email_coop'     => 'nullable|email:rfc,dns',
            'position'       => 'nullable|string|max:255',
            'department'     => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:255',
            'phone_coop'     => 'nullable|string|max:255',
            'country'        => 'nullable|string|max:255',
            'city'           => 'nullable|string|max:255',
            'address'        => 'nullable|string|max:255',
            'company_id'     => 'nullable|string|max:255',
            'role_id'        => 'nullable|string|max:255',
            'birthday'       => 'nullable|date',
            'password'       => "$password|confirmed|min:6|string",
        ];
    }

}
