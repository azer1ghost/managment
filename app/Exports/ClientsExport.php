<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [
            'Type',
            'Fullname',
            'Email1',
            'Email2',
            'Phone1',
            'Phone2',
            'VOEN/GOOEN'
        ];
    }

    public function map($row): array
    {
        return [
            trans('translates.clients_type.' . $row->type),
            $row->fullname,
            $row->email1,
            $row->email2,
            $row->phone1,
            $row->phone2,
            $row->voen
        ];
    }

    public function query()
    {
        return Client::query()
            ->whereNull('client_id')
            ->when(Client::userCannotViewAll(), function ($query){
                $query->where(function ($query){
                    $query
                        ->doesnthave('salesUsers')
                        ->orWhereHas('salesUsers', fn($q) => $q->where('id', auth()->id()));
                });
            })
            ->when($this->filters['free_clients'], fn ($query) => $query->doesnthave('salesUsers'))
            ->when(is_numeric($this->filters['type']), fn ($query) => $query->where('type', $this->filters['type']))
            ->when($this->filters['search'], fn ($query) => $query->where('fullname', 'like', "%{$this->filters['search']}%"))
            ->when($this->filters['salesClient'], fn ($query) => $query->whereHas('salesUsers', fn($q) => $q->where('id', $this->filters['salesClient'])));
    }
}