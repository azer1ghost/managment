<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ClientServiceImport implements ToCollection
{
    // Sütunlar adına görə tapılır, sıranın əhəmiyyəti yoxdur
    const CLIENT_HEADER = 'Müştəri ID';
    const SERVICE_HEADER = 'Xidmət ID';
    const DEPARTMENT_HEADER = 'Şöbə ID';
    const AMOUNT_HEADER = 'Qiymət';

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }

        $headers = $rows->first()->map(fn($header) => trim((string) $header))->flip();

        foreach ([self::CLIENT_HEADER, self::SERVICE_HEADER, self::DEPARTMENT_HEADER, self::AMOUNT_HEADER] as $required) {
            if (!$headers->has($required)) {
                throw new \RuntimeException('Excel-də "' . $required . '" sütunu tapılmadı');
            }
        }

        $clientIds = Client::pluck('id')->flip();
        $serviceIds = Service::pluck('id')->flip();

        foreach ($rows->slice(1) as $row) {
            $clientId = $row[$headers->get(self::CLIENT_HEADER)] ?? null;
            $serviceId = $row[$headers->get(self::SERVICE_HEADER)] ?? null;
            $departmentId = $row[$headers->get(self::DEPARTMENT_HEADER)] ?? null;
            $amount = $row[$headers->get(self::AMOUNT_HEADER)] ?? null;

            if (!is_numeric($clientId) || !is_numeric($serviceId) || !is_numeric($amount)) {
                continue;
            }

            if (!$clientIds->has((int) $clientId) || !$serviceIds->has((int) $serviceId)) {
                continue;
            }

            // Boş şöbə = ümumi qiymət
            $departmentId = is_numeric($departmentId) ? (int) $departmentId : null;

            DB::table('client_service')->updateOrInsert([
                'client_id'     => (int) $clientId,
                'service_id'    => (int) $serviceId,
                'department_id' => $departmentId,
            ], [
                'amount' => $amount,
            ]);
        }
    }
}
