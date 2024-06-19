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
        $voen = $this->isMethod('POST') && !$this->routeIs('clients.store') ? null : $this->request->get('id');

        return [
            'fullname'        => 'required|string|max:255',
//            'email1'          => 'nullable|email:rfc,dns',
            'email1'          => 'nullable|string',
            'phone1'          => 'nullable|string|max:255',
            'address1'        => 'nullable|string|max:255',
            'protocol'        => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls|max:4096',
            'document_type'   => 'nullable|string',
            'sector'          => 'nullable|string',
            'voen'            => 'string|nullable|unique:clients,voen,' . $voen,
            'password'        => 'nullable|string',
            'position'        => 'nullable|string',
            'type'            => 'nullable|integer',
            'client_id'       => 'nullable|integer',
            'detail'          => 'nullable|string',
            'birthday'        => 'nullable|date',
            'main_paper'      => 'nullable|string',
            'qibmain_paper'   => 'nullable|string',
            'user_id'         => 'nullable|integer',
            'ordering'        => 'nullable|integer',
        ];
    }
}