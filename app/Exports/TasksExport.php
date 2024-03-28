<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $startDate = '2024-01-01';
        $endDate = '2024-03-28';

        return Task::whereBetween('created_at', [$startDate, $endDate])->get();
    }
    public function headings(): array
    {
        return [
            '#',
            'Adı',
            'Prioritet',
            'Status',
            'Tapşırıq verən',
            'Şöbə',
            'İstifadəçi',
            'Son tarix',
            'Bitmiş',
            'Yaranma tarixi'
        ];
    }
    public function map($row): array
    {
        return[
            $row->id,
            $row->getAttribute('name'),
            $row->getAttribute('priority'),
            $row->getAttribute('status'),
            $row->getRelationValue('user')->getAttribute('fullname_with_position'),
            $row->taskable->getClassShortName() == 'department' ? $row->taskable->getAttribute('name') : $row->taskable->getRelationValue('department')->getAttribute('name'),
            $row->taskable->getClassShortName() == 'user' ? $row->taskable->getAttribute('fullname_with_position'):
            $row->getAttribute('must_end_at'),
            $row->getAttribute('done_at'),
            $row->getAttribute('created_at'),
        ];
    }
}
