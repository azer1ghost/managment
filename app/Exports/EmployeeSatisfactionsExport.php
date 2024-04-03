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

    public function map($row): array
    {
        return [
            $row->id,
            $row->type,
            optional($row->users)->fullname_with_position,
            optional($row->getAttribute('department_id'))->name,
            optional($row->getAttribute('employee'))->name,
            optional($row->getAttribute('activity'))->name,
            strip_tags($row->content),
            optional($row->reason),
            optional($row->status),
            $row->effectivity,
            $row->note,
            $row->created_at
        ];
    }
}