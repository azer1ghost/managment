<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('manage', Company::class);
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
            'logo'      => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'website'   => 'required|max:255',
            'mail'      => 'required|email:rfc,dns',
            'phone'     => 'required|string|max:255',
            'mobile'    => 'required|string|max:255',
            'address'   => 'required|string|max:255',
            'about'     => 'required|string|max:500',
        ];
    }
}
