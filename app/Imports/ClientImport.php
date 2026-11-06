<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ClientImport implements ToCollection
{
    // Excel başlığı => clients cədvəlinin sütunu
    // Sütunlar adına görə tapılır, sıranın əhəmiyyəti yoxdur
    const ID_HEADER = 'Müştəri ID';

    const PRICE_HEADERS = [
        'Qiymət'                 => 'price',
        'Əsas vərəq qiyməti'     => 'main_paper',
        'QİB əsas vərəq qiyməti' => 'qibmain_paper',
    ];

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }

        $headers = $rows->first()->map(fn($header) => trim((string) $header))->flip();

        if (!$headers->has(self::ID_HEADER)) {
            throw new \RuntimeException('Excel-də "' . self::ID_HEADER . '" sütunu tapılmadı');
        }

        $idIndex = $headers->get(self::ID_HEADER);

        // Yalnız faylda olan qiymət sütunları yenilənir
        $columns = [];
        foreach (self::PRICE_HEADERS as $header => $column) {
            if ($headers->has($header)) {
                $columns[$column] = $headers->get($header);
            }
        }

        foreach ($rows->slice(1) as $row) {
            $id = $row[$idIndex] ?? null;

            if (!is_numeric($id)) {
                continue;
            }

            $values = [];
            foreach ($columns as $column => $index) {
                $value = $row[$index] ?? null;
                $values[$column] = $value === '' ? null : $value;
            }

            // Yalnız mövcud müştərilər yenilənir, yeni müştəri yaradılmır
            $client = Client::find((int) $id);
            if ($client && $values) {
                $client->update($values);
            }
        }
    }
}
