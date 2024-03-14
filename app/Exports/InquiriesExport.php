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
        $startDate = '2024-01-01';
        $endDate = '2024-03-14';

        return Inquiry::whereBetween('created_at', [$startDate, $endDate])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Client',
            'Phone',
            'Email',
            'Subject',
            'Company',
            'Channel',
            'Source',
            'Status',
            'Note',
            'Date',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            optional($row->getParameter('fullname'))->getAttribute('value'),
            optional($row->getParameter('phone'))->getAttribute('value'),
            optional($row->getParameter('email'))->getAttribute('text'),
            optional($row->getParameter('subject'))->getAttribute('text'),
            $row->getRelationValue('company')->getAttribute('name'),
            optional($row->getParameter('contact_method'))->getAttribute('text'),
            optional($row->getParameter('source'))->getAttribute('text'),
            optional($row->getParameter('status'))->getAttribute('text'),
            optional($row->getParameter('note'))->getAttribute('value'),
            $row->getAttribute('created_at'),
        ];
    }
}
