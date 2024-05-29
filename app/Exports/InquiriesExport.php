<?php

namespace App\Exports;

use App\Models\Inquiry;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InquiriesExport implements FromQuery, WithHeadings, WithMapping


{
    protected $filters;
    protected $parameterFilters;
    public function __construct($filters, $parameterFilters)
    {
        $this->filters = $filters;
        $this->parameterFilters = $parameterFilters;
    }

    public function query()
    {
        $query = Inquiry::query();

        foreach ($this->filters as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else if ($value !== null) {
                $query->where($field, 'LIKE', "%{$value}%");
            }
        }

        foreach ($this->parameterFilters as $field => $value) {
            if (!empty($value)) {
                $query->whereHas('parameters', function ($q) use ($field, $value) {
                    $q->where('name', $field)->whereIn('value', $value);
                });
            }
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Client',
            'Phone',
            'Email',
            'Subject',
            'Company',
            'Channel',
            'Source',
            'Status',
            'Note',
            'Date',
        ];
    }

    public function map($row): array
    {
        $note = $row->getAttribute('note');

        return [
            $row->id,
            optional($row->getParameter('fullname'))->getAttribute('value'),
            optional($row->getParameter('phone'))->getAttribute('value'),
            optional($row->getParameter('email'))->getAttribute('text'),
            optional($row->getParameter('subject'))->getAttribute('text'),
            $row->getRelationValue('company')->getAttribute('name'),
            optional($row->getParameter('contact_method'))->getAttribute('text'),
            optional($row->getParameter('source'))->getAttribute('text'),
            optional($row->getParameter('status'))->getAttribute('text'),
            $note,
            $row->getAttribute('created_at'),
        ];
    }
}
