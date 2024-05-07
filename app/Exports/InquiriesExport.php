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
        $startDate = '2024-04-03';
        $endDate = '2024-04-30';

        return Inquiry::where('user_id', 102)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
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
        $note = $row->getAttribute('note');

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
            $note,
            $row->getAttribute('created_at'),
        ];
    }
}
