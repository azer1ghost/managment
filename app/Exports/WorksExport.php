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

        // BURDA SƏNİN EXCEL SIRAN SAXLANILIR
        $this->headings = [
            'Yaradılma Tarixi (Gün)', 'Yaradılma Tarixi (Saat)',
            'Bitmə Tarixi (Gün)', 'Bitmə Tarixi (Saat)',
            'Şöbə',
            'Koordinator',
            'Sorğu nömrəsi',
            'Nəqliyyat nömrəsi',
            'Sıralayıcı Əməkdaş',
            'Analitik Əməkdaş',
            'İcraçı Əməkdaş',
            'Sistemdə (ASAN IMZA)',
            'Müştəri adı',
            'Şəxs (Fiziki / Hüquqi)',
            'Xidmət',
            'Status',
            'Təyinat Orqanı',
            'Detallar',

            'Tam məbləğ',
            'Qaime Tarixi (Gün)',
            'Qaime nomresi',
            'Ödəmə Üsulu',
            'Vasitəçi',

            'Əsas Məbləğ Ödəniş Tarixi',
            'ƏDV Ödəniş Tarixi',
            'Ödənmiş məbləğ',
            'Qalıq',
            'Sisteme Tarixi (Gün)', 'Sisteme Tarixi (Saat)',
            'Toplam Məbləğ',
            'Referans',
            'Son Dəyişiklik Tarixi',
        ];

        // Əlavə servis parametrləri
        foreach (Service::serviceParametersExport() as $servicesParameter) {
            if (!in_array($servicesParameter['data']->id, [51, 52, 53, 54, 56, 57, 60])) {
                $this->headings[] = $servicesParameter['data']->label;
            }
        }
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        $customerEngagement = $row->customerEngagement;
        $agent = $customerEngagement?->user;
        $reference = $customerEngagement?->partner;

        $types = [
            Client::LEGAL => 0,
            Client::PHYSICAL => 1,
            Client::FOREIGNPHYSICAL => 2,
            Client::FOREIGNLEGAL => 3,
        ];

        $coordinator = $row->client?->coordinators?->first();
        $coordinatorName = $coordinator
            ? $coordinator->name . ' ' . $coordinator->surname
            : 'Koordinator yoxdur';

        $userName = $row->user
            ? $row->user->name . ' ' . $row->user->surname
            : 'İcraçı yoxdur';

        $sorterName = $row->sorter
            ? $row->sorter->name . ' ' . $row->sorter->surname
            : 'Sıralayıcı yoxdur';

        $analystName = $row->analyst
            ? $row->analyst->name . ' ' . $row->analyst->surname
            : 'Analitik yoxdur';

        $agentName = $agent
            ? $agent->name . ' ' . $agent->surname
            : 'Vasitəçi yoxdur';

        $asanImzaName = $row->asanImza
            ? optional($row->asanImza->user)->name . ' - ' . optional($row->asanImza->company)->name
            : trans('translates.filters.select');

        // Tam məbləğ və ödənişlər
        $totalAmount =
            $row->getParameter($row::VAT) +
            $row->getParameter($row::AMOUNT) +
            $row->getParameter($row::ILLEGALAMOUNT);

        $paidTotal =
            $row->getParameter($row::PAID) +
            $row->getParameter($row::VATPAYMENT) +
            $row->getParameter($row::ILLEGALPAID);

        $balance = $totalAmount - $paidTotal;

        $maps = [
            // 1–4: Yaradılma / Bitmə tarix & saat
            optional($row->created_at)?->toDateString(),
            optional($row->created_at)?->toTimeString(),
            optional($row->datetime)?->toDateString(),
            optional($row->datetime)?->toTimeString(),

            // 5: Şöbə
            $row->department?->short_name,

            // 6: Koordinator
            $coordinatorName,

            // 7–8: Sorğu / Nəqliyyat
            $row->declaration_no,
            $row->transport_no,

            // 9–11: İşçilər
            $sorterName,
            $analystName,
            $userName,

            // 12: Sistem (ASAN IMZA)
            $asanImzaName,

            // 13: Müştəri adı
            $row->client?->fullname ?? '-',

            // 14: Şəxs (Fiziki / Hüquqi)
            $types[$row->client?->type] ?? 'Unknown',

            // 15: Xidmət
            $row->service?->name,

            // 16: Status
            trans('translates.work_status.' . $row->status),

            // 17: Təyinat Orqanı
            trans('translates.work_destination.' . $row->destination) ?? 'Təyinat orqanı boşdur',

            // 18: Detallar
            strip_tags($row->detail),

            // 19: Tam məbləğ
            $totalAmount,

            // 20: Qaime Tarixi (Gün)
            optional($row->invoiced_date)?->toDateString(),

            // 21: Qaime nomresi
            $row->code,

            // 22: Ödəmə Üsulu
            trans('translates.payment_methods.' . $row->payment_method),

            // 23: Vasitəçi
            $agentName,

            // 24: Əsas Məbləğ Ödəniş Tarixi
            optional($row->paid_at)?->format('d/m/Y') ?? 'Tam Ödəniş olmayıb',

            // 25: ƏDV Ödəniş Tarixi
            optional($row->vat_date)?->format('d/m/Y') ?? 'ƏDV Ödənişi olmayıb',

            // 26: Ödənmiş məbləğ
            $paidTotal,

            // 27: Qalıq
            $balance,

            // 28–29: Sisteme Tarixi (Gün / Saat)
            optional($row->injected_at)?->toDateString(),
            optional($row->injected_at)?->toTimeString(),

            // 30: Toplam Məbləğ
            $row->total_amount ?? 'Məlumat yoxdur',

            // 31: Referans
            $reference?->name ?? 'Referans yoxdur',

            // 32: Son Dəyişiklik Tarixi
            optional($row->updated_at)?->format('d/m/Y H:i:s'),
        ];

        // Dinamik servis parametrləri (constructor ilə eyni filter)
        foreach (Service::serviceParametersExport() as $servicesParameter) {
            if (!in_array($servicesParameter['data']->id, [51, 52, 53, 54, 56, 57, 60])) {
                $maps[] = $row->getParameter($servicesParameter['data']->id);
            }
        }

        return $maps;
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
            'B' => 20,
            'C' => 25,
            'D' => 15,
            'E' => 30,
            'F' => 35,
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
