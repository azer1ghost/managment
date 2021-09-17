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
            'name'           => 'required|string|max:255',
            'surname'        => 'nullable|string|max:255',
            'father'         => 'nullable|string|max:255',
            'gender'         => 'nullable|string|max:255',
            'serial'         => 'nullable|string|max:255',
            'serial_pattern' => 'nullable|string|max:255',
            'email'          => 'required|email:rfc,dns|unique:clients',
            'email_coop'     => 'required|email:rfc,dns|unique:clients',
            'phone'          => 'nullable|string|max:255',
            'phone_coop'     => 'nullable|string|max:255',
            'address'        => 'nullable|string|max:255',
            'company'        => 'nullable|string',
        ];
    }

}