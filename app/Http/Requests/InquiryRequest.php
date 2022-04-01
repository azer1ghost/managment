<?php

namespace App\Http\Requests;

use App\Models\Inquiry;
use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date'      => 'date|required',
            'time'      => 'required',
            'fullname'  => "nullable|string|max:255",
            'phone'     => "filled|string|max:255",
            'subject'   => "filled|int|max:255",
            'kind'      => "filled|int|max:255",
            'source'    => "filled|int|max:255",
            'note'      => "nullable|string|max:5000",
            'redirected'=> "nullable|string|max:255",
            'status'    => "filled|int",
            'company_id'=> "required|int|max:11",
            'contact_method' => "filled|int",
            'operation' => "filled|int",
            'is_out' => 'required|numeric',
            'client_id' => 'nullable|integer',
            'checking' => 'nullable|numeric',
            'alarm' => 'date|nullable',
        ];
    }
}
