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
        $startDate = '2023-08-01';
        $endDate = '2023-09-30';

        return Inquiry::whereBetween('created_at', [$startDate, $endDate])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Client',
            'SalesClient',
            'Phone',
            'SalesPhone',
            'Company',
            'Channel',
            'Date',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            optional($row->getParameter('fullname'))->getAttribute('value'),
            optional($row->getRelationValue('client'))->getAttribute('name'),
            optional($row->getParameter('phone'))->getAttribute('value'),
            optional($row->getRelationValue('client'))->getAttribute('phone'),
            $row->getRelationValue('company')->getAttribute('name'),
            optional($row->getParameter('contact_method'))->value,
            $row->getAttribute('created_at'),
        ];
    }
}
