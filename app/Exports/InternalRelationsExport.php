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

    public function map($internalRelation): array
    {
        return [
            $internalRelation->id,
            $internalRelation->departments->name,
            $internalRelation->case,
            $internalRelation->applicant,
            $internalRelation->user_id == null ? $internalRelation->reciever : $internalRelation->users->getFullnameWithPositionAttribute(),
            $internalRelation->tool,
            $internalRelation->contact_time,
            $internalRelation->done_at,
        ];
    }
}
