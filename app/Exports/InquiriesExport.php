<?php

namespace App\Exports;

use App\Models\Inquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InquiriesExport implements FromCollection, WithHeadings, WithMapping


{
    public function collection()
    {
        return Inquiry::get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Phone',
            'Client',
            'Company'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            optional($row->getParameter('phone'))->getAttribute('value'),
            optional($row->getParameter('fullname'))->getAttribute('value'),
            $row->getAttribute('company_id')
        ];
    }
}
