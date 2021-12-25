<?php

namespace App\Exports;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Service;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorksExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected array $filters = [],$dateRanges = [],$dateFilters = [];
    protected WorkRepositoryInterface $workRepository;
    protected array $headings = [];

    public function __construct(WorkRepositoryInterface $workRepository, array $filters = [], array $dateFilters = [])
    {
        $this->workRepository = $workRepository;
        $this->filters = $filters;
        $this->dateFilters = $dateFilters;

        $this->headings = [
//            trans('translates.columns.created_by'),
            trans('translates.fields.department'),
            trans('translates.columns.user'),
            trans('translates.navbar.asan_imza'),
            trans('translates.general.work_service'),
            'Müştəri adı',
            'Status',
        ];

        foreach (Service::serviceParameters() as $serviceParameter) {
            $this->headings[] = $serviceParameter['data']->getAttribute('label');
        }

        $this->headings = array_merge($this->headings, [
                trans('translates.columns.created_at'),
                'Bitirilib',
                'Təstiqlənib',
            ]
        );
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        $maps = [
            $row->getRelationValue('department')->getAttribute('name'),
            $row->getRelationValue('user')->getAttribute('fullname'),
            $row->getRelationValue('asanImza')->getAttribute('user_with_company'),
            $row->getRelationValue('service')->getAttribute('name'),
            $row->getRelationValue('client')->getAttribute('fullname_with_voen'),
            trans('translates.work_status.' . $row->status),
        ];

        foreach (Service::serviceParameters() as $serviceParameter) {
            $maps[] = $row->getParameter($serviceParameter['data']->getAttribute('id'));
        }

        return array_merge($maps, [
            $row->getAttribute('created_at'),
            $row->getAttribute('datetime') ?? 'Xeyir',
            $row->getAttribute('verified_at') ?? 'Xeyir'
        ]);
    }

    public function query()
    {
        return $this->workRepository->allFilteredWorks($this->filters, $this->dateFilters);
    }
}
