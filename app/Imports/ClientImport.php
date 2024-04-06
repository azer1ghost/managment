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
                'ordering' => $row[1],
            ]);
        }

    }
}
