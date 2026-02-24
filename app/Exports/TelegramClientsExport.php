<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TelegramClientsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected Carbon $fromDate;
    protected Carbon $toDate;
    protected Collection $clients;

    public function __construct(Carbon $fromDate, Carbon $toDate)
    {
        $this->fromDate = $fromDate->copy()->startOfDay();
        $this->toDate = $toDate->copy()->endOfDay();
        $this->loadClients();
    }

    /**
     * Telegram bot Excel export üçün müştərilər (clients) cədvəli — tək SQL sorğusu
     */
    protected function loadClients(): void
    {
        $sql = "
            SELECT
                c.id                                   AS client_id,
                c.fullname                             AS musteri_adi,
                c.voen                                 AS voen,
                c.email1                               AS email,
                c.phone1                               AS phone1,
                JSON_UNQUOTE(JSON_EXTRACT(c.deleted_items, '$.phone2')) AS phone2,
                JSON_UNQUOTE(JSON_EXTRACT(c.deleted_items, '$.phone3')) AS phone3,
                c.sector                               AS sektor,
                MAX(p.name)                            AS vasiteci,
                MAX(u3.name)                           AS referans_sexs,
                MIN(w.created_at)                      AS ilk_is_tarixi,
                MAX(w.created_at)                      AS son_is_tarixi,
                MAX(u1.name)                           AS koordinator,
                MAX(u2.name)                           AS sales,
                SUM(CASE WHEN YEAR(w.created_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS is_sayi_bu_il,
                COUNT(DISTINCT CASE WHEN YEAR(w.created_at) = YEAR(CURDATE()) THEN DATE_FORMAT(w.created_at, '%Y-%m') END) AS aktiv_ay_sayi_bu_il,
                MAX(JSON_UNQUOTE(JSON_EXTRACT(d.name, '$.az'))) AS son_sobe,
                MAX(w.id)                              AS son_is_id
            FROM clients c
            LEFT JOIN clients c2 ON c.client_id = c2.id
            LEFT JOIN customer_engagements ce ON c.id = ce.client_id
            LEFT JOIN partners p ON ce.partner_id = p.id
            LEFT JOIN users u3 ON ce.user_id = u3.id
            LEFT JOIN coordinators_clients_relationship ccr ON c.id = ccr.client_id
            LEFT JOIN sales_clients_relationship scr ON c.id = scr.client_id
            LEFT JOIN users u1 ON ccr.user_id = u1.id
            LEFT JOIN users u2 ON scr.user_id = u2.id
            LEFT JOIN works w ON c.id = w.client_id
            LEFT JOIN departments d ON w.department_id = d.id
            WHERE c.deleted_at IS NULL
              AND w.deleted_at IS NULL
              AND w.created_at >= ?
              AND w.created_at < ?
            GROUP BY c.id
            ORDER BY son_is_tarixi DESC
        ";

        $this->clients = collect(DB::select($sql, [$this->fromDate, $this->toDate]));
    }

    public function collection(): Collection
    {
        return $this->clients;
    }

    public function headings(): array
    {
        return [
            'Müştəri ID',
            'Müştəri adı',
            'VÖEN',
            'Email',
            'Telefon 1',
            'Telefon 2',
            'Telefon 3',
            'Sektor',
            'Vasitəçi',
            'Referans şəxs',
            'İlk iş tarixi',
            'Son iş tarixi',
            'Koordinator',
            'Sales',
            'İş sayı (bu il)',
            'Aktiv ay sayı (bu il)',
            'Son şöbə',
            'Son iş ID',
        ];
    }

    public function map($row): array
    {
        $row = (object) $row;
        $ilkIs = $row->ilk_is_tarixi ? Carbon::parse($row->ilk_is_tarixi)->format('Y-m-d H:i:s') : '-';
        $sonIs = $row->son_is_tarixi ? Carbon::parse($row->son_is_tarixi)->format('Y-m-d H:i:s') : '-';
        return [
            $row->client_id,
            $row->musteri_adi ?? '-',
            $row->voen ?? '-',
            $row->email ?? '-',
            $row->phone1 ?? '-',
            $row->phone2 ?? '-',
            $row->phone3 ?? '-',
            $row->sektor ?? '-',
            $row->vasiteci ?? '-',
            $row->referans_sexs ?? '-',
            $ilkIs,
            $sonIs,
            $row->koordinator ?? '-',
            $row->sales ?? '-',
            (int) ($row->is_sayi_bu_il ?? 0),
            (int) ($row->aktiv_ay_sayi_bu_il ?? 0),
            $row->son_sobe ?? '-',
            $row->son_is_id ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
