<?php

namespace App\Exports;

use App\Models\Inquiry;
use App\Models\Task;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InquiriesExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    protected array $filters = [], $parameterFilters = [];

    public function collection()
    {
        $startDate = '2024-03-01';
        $endDate = '2024-07-30';

        return Inquiry::whereBetween('created_at', [$startDate, $endDate])->with('task')->get();
    }
//    public function query()
//    {
//        $query = Inquiry::query();
//
//        foreach ($this->filters as $field => $value) {
//            if (is_array($value)) {
//                $query->whereIn($field, $value);
//            } else if ($value !== null) {
//                $query->where($field, 'LIKE', "%{$value}%");
//            }
//        }
//
//        return $query;
//    }

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
            'Task Result',
        ];
    }

    public function map($row): array
    {
        $note = $row->getAttribute('note');
        $task = Task::where('inquiry_id', $row->id)->first();
        $resultValue = null;
        if ($task) {
            if ($result = $task->result()->first())
            $resultValue = $result->content;
        }
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
            $resultValue
        ];
    }
}
