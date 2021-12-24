<?php

namespace App\Exports;

use App\Interfaces\WorkRepositoryInterface;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorksExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected array $filters = [],$dateRanges = [],$dateFilters = [];
    protected WorkRepositoryInterface $workRepository;

    public function __construct(WorkRepositoryInterface $workRepository, array $filters = [], array $dateFilters = [])
    {
        $this->workRepository = $workRepository;
        $this->filters = $filters;
        $this->dateFilters = $dateFilters;
    }

    public function headings(): array
    {
        return [
            'Created By',
            'Department	',
            'User',
            'Asan Imza',
            'Service',
            'Client Name',
            'Status',
            'Created At',
            'Date',
            'Verified',
        ];
    }

    public function map($row): array
    {
        return [
            $row->getRelationValue('creator')->getAttribute('fullname'),
            $row->getRelationValue('department')->getAttribute('name'),
            $row->getRelationValue('user')->getAttribute('fullname'),
            $row->getRelationValue('asanImza')->getAttribute('user_with_company'),
            $row->getRelationValue('service')->getAttribute('name'),
            $row->getRelationValue('client')->getAttribute('fullname_with_voen'),
            trans('translates.work_status.' . $row->status),
            $row->getAttribute('created_at'),
            $row->getAttribute('datetime'),
            $row->getAttribute('verified_at'),
        ];
    }

    public function query()
    {
        return $this->workRepository->allFilteredWorks($this->filters, $this->dateFilters);
    }
}
