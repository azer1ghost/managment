<?php

namespace App\Exports;

use App\Interfaces\CreditorRepositoryInterface;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CreditorsExport implements  FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    protected array $filters = [];

    protected CreditorRepositoryInterface $creditorRepository;

    public function __construct(CreditorRepositoryInterface $creditorRepository, array $filters = [] )
    {
        $this->filters = $filters;
        $this->creditorRepository = $creditorRepository;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tədarükçü',
            'Şirkət',
            'Məbləğ',
            'Ədv',
            'Əsas Məbləğ Ödəniş',
            'Ədv Ödəniş',
            'Status',
            'Qaime',
            'Qaime Tarixi',
            'Qeyd',
        ];
    }


    public function map($row): array
    {
        return [
            $row->id,
            $row->getSupplierName(),
            $row->getRelationValue('company')->getAttribute('name'),
            $row->getAttribute('amount'),
            $row->getAttribute('vat'),
            $row->getAttribute('paid'),
            $row->getAttribute('vat_paid'),
            trans('translates.creditors.statuses.'.$row->getAttribute('status')),
            $row->getAttribute('overhead'),
            $row->getAttribute('overhead_at'),
            $row->getAttribute('note'),
        ];
    }
    public function query()
    {
        return $this->creditorRepository->allFilteredCreditors($this->filters);
    }
}

