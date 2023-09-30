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
            $row->getSupplierName(),
            $row->getRelationValue('company')->getAttribute('name'),
            optional($row)->getAttribute('amount') ?? 0,
            optional($row)->getAttribute('vat') ?? 0,
            optional($row)->getAttribute('paid') ?? 0,
            optional($row)->getAttribute('vat_paid') ?? 0,
            trans('translates.creditors.statuses.'.$row->getAttribute('status')),
            optional($row->getAttribute('overhead')),
            optional($row->getAttribute('overhead_at')),
            $row->getAttribute('overhead_at'),
            $row->getAttribute('note'),
        ];
    }
}

