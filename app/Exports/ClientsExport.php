<?php

namespace App\Exports;

use App\Interfaces\ClientRepositoryInterface;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected array $filters = [];
    protected ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository, array $filters = [])
    {
        $this->filters = $filters;
        $this->clientRepository = $clientRepository;
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
            $row->getAttribute('fullname'),
            $row->getAttribute('email1'),
            $row->getAttribute('email2'),
            $row->getAttribute('phone1'),
            $row->getAttribute('phone2'),
            $row->getAttribute('voen')
        ];
    }

    public function query()
    {
        return $this->clientRepository->allFilteredClients($this->filters);
    }
}