<?php

namespace App\Exports;


use App\Models\EmployeeSatisfaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class EmployeeSatisfactionsExport implements FromCollection, WithHeadings, WithMapping

{
    public function collection()
    {
        return EmployeeSatisfaction::all();
    }

    public function headings(): array
    {
        return [
            '#',
            'Type',
            'User',
            'Department',
            'Employee',
            'Activity',
            'Content',
            'Reason',
            'Status',
            'Effectivity',
            'Note',
            'Yaranma Tarixi'
        ];
    }
    public static function types()
    {
        return [
            1 => 'Təklif',
            2 => 'Qarşılaşdığınız Çətinlik', // 'Complaint'
            3 => 'Uyğunsuzluq', // 'Incompatibility'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            self::types()[$row->type],
            optional($row->users)->fullname_with_position,
            optional($row->departments)->name,
            optional($row->employees)->fullname_with_position,
            strip_tags($row->activity),
            strip_tags($row->content),
            optional($row->reason)->text,
            optional($row->status)->text,
            $row->effectivity,
            $row->note,
            $row->created_at
        ];
    }
}