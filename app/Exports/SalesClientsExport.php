<?php

namespace App\Exports;

use App\Models\SalesClient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesClientsExport implements  WithHeadings, FromCollection
{
    public function headings(): array
    {
        return [
            'ID',
            'Fullname',
            'Detal',
            'Phone',
            'Voen',
        ];
    }

    public function collection()
    {
        return SalesClient::get(['id', 'name', 'detail', 'phone', 'voen']);
    }
}
