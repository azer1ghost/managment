<?php

namespace App\Exports;

use App\Models\Creditor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CreditorsExport implements  WithHeadings, FromCollection
{
    public function headings(): array
    {
        return [
            'ID',
            'Tədarükçü',
            'Şirkət',
            'Məbləğ',
            'Ədv',
            'Əsas Məbləğ Ödəniş tarixi',
            'Ədv ödəniş tarixi',
            'Status',
            'Qaime',
            'Qaime Tarixi',
            'Qeyd',
        ];
    }

    public function collection()
    {
        return Creditor::get(['id', 'supplier_id', 'company_id', 'amount', 'paid', 'vat', 'vat_paid', 'status', 'overhead', 'overhead_at', 'note']);
    }
    public function map($row): array
    {
        return [
            $row->id,
            optional($row->getParameter('supplier_id'))->getAttribute('name') ?? '',
            optional(optional($row->getRelationValue('company_id'))->getAttribute('name')) ?? '',
            optional($row->getParameter('amount'))->getAttribute('value') ?? 0,
            optional($row->getParameter('paid'))->getAttribute('value') ?? 0,
            optional($row->getParameter('vat'))->getAttribute('value') ?? 0,
            optional($row->getParameter('vat_paid'))->getAttribute('value') ?? 0,
            optional($row->getParameter('status'))->getAttribute('name') ?? '',
            optional($row->getParameter('overhead'))->getAttribute('value') ?? 0,
            optional($row->getParameter('overhead_at'))->getAttribute('value') ?? '',
            optional($row->getParameter('note'))->getAttribute('text') ?? '',
            $row->getAttribute('created_at'),
        ];
    }
}

