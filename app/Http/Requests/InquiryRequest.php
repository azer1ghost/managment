<?php

namespace App\Http\Requests;

use App\Models\Inquiry;
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
        return $this->user()->can('create', Inquiry::class);
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
            'client'    => "filled|string|max:255",
            'fullname'  => "filled|string|max:255",
            'phone'     => "filled|string|max:255",
            'subject'   => "filled|string|max:255",
            'kind'      => "filled|string|max:255",
            'source'    => "filled|string|max:255",
            'note'      => "filled|string|max:255",
            'redirected'=> "filled|string|max:255",
            'status'    => "filled|string",
            'company_id'=> "required|int|max:11",
            'contact_method' => "filled|int",
            'operation' => "filled|int",
        ];
    }
}
