<?php

namespace App\Exports;

use App\Models\InternalRelation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InternalRelationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return InternalRelation::all();
    }

    public function headings(): array
    {
        return [
            '#',
            'Department',
            'Əlaqə Saxlanılacaq Hal',
            'Müraciət Edən Şəxs',
            'Əlaqə Saxlanılacaq Şəxs',
            'Əlaqə Vasitəsi',
            'Əlaqə Zamanı',
            'Tamamlanma Zamanı',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->getRelationValue('departments')->getAttribute('name'),
            $row->getAttribute('case'),
            $row->getAttribute('applicant'),
            $row->getAttribute('user_id') == null ? $row->getAttribute('reciever') : $row->getRelationValue('users')->getFullnameWithPositionAttribute(),
            $row->getAttribute('tool'),
            $row->getAttribute('contact_time'),
            $row->getAttribute('done_at'),
        ];
    }
}
