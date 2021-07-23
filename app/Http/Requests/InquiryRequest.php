<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date'      => 'required',
            'time'      => "required",
            'client'    => "string|max:255",
            'fullname'  => "string|max:255",
            'phone'     => "string|max:255",
            'subject'   => "required|string|max:255",
            'kind'      => "nullable|string|max:255",
            'source'    => "string|max:255",
            'note'      => "nullable|string|max:255",
            'redirected'=> "string|max:255",
            'status'    => "required|string",
            'company_id'=> "required|int|max:11",
        ];
    }
}
