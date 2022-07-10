<?php

namespace App\Exports;

use App\Models\Inquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InquiriesExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'Name',
            'Company',
            'Phone'
        ];
    }


    public function collection()
    {
        return Inquiry::all();
    }
}
