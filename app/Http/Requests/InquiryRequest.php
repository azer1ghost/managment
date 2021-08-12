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
    public function authorize(): bool
    {
        return $this->user()->can('create', Inquiry::class);
    }

//    protected function prepareForValidation()
//    {
//        $this->merge([
//            'client'   => $this->client ?? null,
//        ]);
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'date'      => 'date|required',
            'time'      => 'required',
            'client'    => "nullable|string|max:255",
            'fullname'  => "nullable|string|max:255",
            'phone'     => "filled|string|max:255",
            'subject'   => "filled|int|max:255",
            'kind'      => "filled|int|max:255",
            'source'    => "filled|int|max:255",
            'note'      => "nullable|string|max:255",
            'redirected'=> "nullable|string|max:255",
            'status'    => "filled|int",
            'company_id'=> "required|int|max:11",
            'contact_method' => "filled|int",
            'operation' => "filled|int",
        ];
    }
}
