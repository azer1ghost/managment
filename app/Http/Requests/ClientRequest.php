<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullname'        => 'required|string|max:255',
            'father'          => 'nullable|string|max:255',
            'gender'          => 'nullable|string|max:255',
            'fin'             => 'nullable|string|max:255',
            'serial'          => 'nullable|string|max:255',
            'serial_pattern'  => 'nullable|string|max:255',
            'email1'          => 'nullable|email:rfc,dns',
            'email2'          => 'nullable|email:rfc,dns',
            'phone1'          => 'nullable|string|max:255',
            'phone2'          => 'nullable|string|max:255',
            'address1'        => 'nullable|string|max:255',
            'address2'        => 'nullable|string|max:255',
            'voen'            => 'filled|string',
            'position'        => 'nullable|string',
            'type'            => 'nullable|boolean',
            'client_id'       => 'nullable|integer',
            'detail'          => 'nullable|string',
        ];
    }
}