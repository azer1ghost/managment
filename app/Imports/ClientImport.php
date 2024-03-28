<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ClientImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Client::updateOrCreate([
                'id' => $row[0],
            ], [
                'fullname' => $row[1],
                'fin' => $row[2],
                'email1' => $row[3],
                'email2' => $row[4],
                'phone1' => $row[5],
                'phone2' => $row[6],
                'voen' => $row[7],
                'adress1' => $row[8],
                'detail' => $row[9],
                'price' => $row[10],
                'sector' => $row[11],
                'phone3' => $row[12],
                'main_paper' => $row[13],
                'qibmain_paper' => $row[14],
            ]);
        }

    }
}
