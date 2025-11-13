<?php

namespace App\Exports;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Client;
use App\Models\Service;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorksExport implements FromQuery, WithMapping, WithHeadings, WithColumnWidths, ShouldAutoSize, WithStyles, WithChunkReading
{
    use Exportable;

    protected array $filters = [], $dateFilters = [], $headings = [];
    protected WorkRepositoryInterface $workRepository;

    public function __construct(WorkRepositoryInterface $workRepository, array $filters = [], array $dateFilters = [])
    {
        $this->workRepository = $workRepository;
        $this->filters = $filters;
        $this->dateFilters = $dateFilters;

        // EXCEL-DEKI FINAL 45 SUTUN
        $this->headings = [
            'Yaradılma Tarixi (Gün)',
            'Yaradılma Tarixi (Saat)',
            'Bitmə Tarixi (Gün)',
            'Bitmə Tarixi (Saat)',
            'Şöbə',
            'Koordinator',
            'Sorğu nömrəsi',
            'Nəqliyyat nömrəsi',
            'Sıralayıcı Əməkdaş',
            'Analitik Əməkdaş',
            'İcraçı Əməkdaş',
            'Sistem (ASAN IMZA)',
            'Müştəri adı',
            'Müştəri növü',
            'Xidmət',
            'Status',
            'Təyinat Orqanı',
            'Detallar',

            'GB',
            'Kod sayı',
            'Say',
            'Əsas Vərəq',
            'Əsas Məbləğ',
            'ƏDV',
            'Digər məbləğ',

            'Tam məbləğ',
            'Faktiki məbləğ (Toplam məbləğ)',
            'Qaimə tarixi',
            'Qaimə nömrəsi',

            'Əsas Məbləğdən Ödənilən',
            'Əsas Məbləğ Ödəniş Tarixi',
            "ƏDV'dən Ödənilən",
            'ƏDV Ödəniş Tarixi',
            'Digər Ödəniş',

            'Faktiki ödəniş (Ödənmiş məbləğ)',
            'Ödəmə Üsulu',

            'Borc əsas',
            'Borc ƏDV',
            'Borc ümumi(Qalıq)',

            'Satış Əməkdaşı',
            'Vasitəçi',
            'Referans',

            'Sisteme Tarixi (Gün)',
            'Sisteme Tarixi (Saat)',

            'Son Dəyişiklik Tarixi',
        ];
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        // Parametr ID-ləri
        $gb              = $row->getParameter(17);
        $kodSayi         = $row->getParameter(18);
        $esasMebleg      = $row->getParameter(33);
        $say             = $row->getParameter(20);
        $edv             = $row->getParameter(34);
        $diger           = $row->getParameter(38);

        $esasPaid        = $row->getParameter(35);
        $edvPaid         = $row->getParameter(36);
        $digerPaid       = $row->getParameter(37);

        // Hesablamalar
        $tamMebleg = $esasMebleg + $edv + $diger;
        $odenmis   = $esasPaid + $edvPaid + $digerPaid;

        $borcEsas  = $esasMebleg - $esasPaid;
        $borcEdv   = $edv - $edvPaid;
        $borcUmumi = $tamMebleg - $odenmis;

        $types = [
            Client::LEGAL => 0,
            Client::PHYSICAL => 1,
            Client::FOREIGNPHYSICAL => 2,
            Client::FOREIGNLEGAL => 3,
        ];

        return [

            optional($row->created_at)?->toDateString(),
            optional($row->created_at)?->toTimeString(),

            optional($row->datetime)?->toDateString(),
            optional($row->datetime)?->toTimeString(),

            $row->department?->short_name,
            optional($row->client?->coordinators?->first())?->fullname ?? '-',

            $row->declaration_no,
            $row->transport_no,

            optional($row->sorter)?->fullname ?? '-',
            optional($row->analyst)?->fullname ?? '-',
            optional($row->user)?->fullname ?? '-',

            $row->asanImza ? optional($row->asanImza->user)->name . ' - ' . optional($row->asanImza->company)->name : '-',

            $row->client?->fullname ?? '-',
            $types[$row->client?->type] ?? 'Unknown',

            $row->service?->name ?? '-',
            trans('translates.work_status.' . $row->status),
            trans('translates.work_destination.' . $row->destination),

            strip_tags($row->detail),

            // PARAMETRLƏR
            $gb,
            $kodSayi,
            $say,
            $row->main_paper,  // Əsas Vərəq (ID sonra deyərsən)
            $esasMebleg,
            $edv,
            $diger,

            // HESABLANANLAR
            $tamMebleg,
            $row->total_amount,
            optional($row->invoiced_date)?->toDateString(),
            $row->code,

            $esasPaid,
            optional($row->paid_at)?->format('d/m/Y'),
            $edvPaid,
            optional($row->vat_date)?->format('d/m/Y'),
            $digerPaid,

            $odenmis,
            trans('translates.payment_methods.' . $row->payment_method),

            $borcEsas,
            $borcEdv,
            $borcUmumi,

            optional($row->client?->sales?->first())?->fullname ?? '-',
            optional($row->customerEngagement?->user)?->fullname ?? '-',
            optional($row->customerEngagement?->partner)?->name ?? '-',

            optional($row->injected_at)?->toDateString(),
            optional($row->injected_at)?->toTimeString(),

            optional($row->updated_at)?->format('d/m/Y H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW,
                    ],
                ],
            ],
        ];
    }

    public function query()
    {
        return $this->workRepository->allFilteredWorks($this->filters, $this->dateFilters)
            ->with([
                'department:id,short_name',
                'client:id,fullname,type',
                'client.coordinators:id,name,surname',
                'client.sales:id,name,surname',
                'user:id,name,surname',
                'sorter:id,name,surname',
                'analyst:id,name,surname',
                'service:id,name',
                'asanImza:id,user_id,company_id',
                'asanImza.user:id,name',
                'asanImza.company:id,name',
                'customerEngagement.user:id,name,surname',
                'customerEngagement.partner:id,name',
            ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
