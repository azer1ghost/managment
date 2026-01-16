<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PdfSignatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pdf' => 'required|file|mimes:pdf|max:20480', // 20MB max
            'signature_name' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'pdf.required' => 'PDF faylı seçilməlidir.',
            'pdf.mimes' => 'Fayl PDF formatında olmalıdır.',
            'pdf.max' => 'PDF faylının ölçüsü 20MB-dan çox ola bilməz.',
            'signature_name.required' => 'İmza seçilməlidir.',
        ];
    }
}
