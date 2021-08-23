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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $logo = $this->isMethod('POST') ? 'required' : 'nullable';
        return [
            'name'      => 'required|string|max:255',
            'logo'      => "$logo|image|mimes:jpg,png,jpeg,gif,svg|max:2048",
            'website'   => 'required|max:255',
            'mail'      => 'required|email:rfc,dns',
            'phone'     => 'required|string|max:255',
            'mobile'    => 'required|string|max:255',
            'call_center' => 'required|string|max:255',
            'address'   => 'required|string|max:255',
            'about'     => 'required|string|max:500',
            'keywords' => 'required|string|max:255',
            'is_inquirable' => 'nullable',
            'socials'   => 'nullable|array',
            'socials.*.id'   => 'nullable|string',
            'socials.*.name'   => 'required|string',
            'socials.*.url'   => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'socials.*.name.required'  => 'The Social name is required',
            'socials.*.url.required'   => 'The Social url is required',
            'socials.*.name.string'    => 'The Social name field should be a string',
            'socials.*.url.string'     => 'The Social url field should be a string',
        ];
    }
}
