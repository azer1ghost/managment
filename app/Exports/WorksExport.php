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

        $this->headings = [
            'Sorğu nömrəsi',
            'İş Kodu',
            'Şöbə',
            'Koordinator',
            'İcraçı Əməkdaş',
            'Sistemdə (ASAN IMZA)',
            'Müştəri adı',
            'Şəxs (Fiziki / Hüquqi)',
            'Xidmət',
            'Təyinat Orqanı',
            'Status',
            'Detallar',
            'Sənədlər',
            'Əsas Məbləğ Ödəniş Tarixi',
            'ƏDV Ödəniş Tarixi',
            'Tam məbləğ',
            'Ödənmiş məbləğ',
            'Qalıq',
            'Yaradılma Tarixi (Gün)', 'Yaradılma Tarixi (Saat)',
            'Sisteme Tarixi (Gün)', 'Sisteme Tarixi (Saat)',
            'Toplam Məbləğ',
            'Ödəmə Üsulu',
            'Vasitəçi',
            'Referans',
            'Son Dəyişiklik Tarixi',
        ];

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
        $coordinatorName = $coordinator ? $coordinator->name . ' ' . $coordinator->surname : 'Koordinator yoxdur';

        $userName = $row->user ? $row->user->name . ' ' . $row->user->surname : 'İcraçı yoxdur';
        $agentName = $agent ? $agent->name . ' ' . $agent->surname : 'Vasitəçi yoxdur';

        $maps = [
            $row->declaration_no,
            $row->code,
            $row->department?->short_name,
            $coordinatorName,
            $userName,
            $row->asanImza?->user_with_company ?? trans('translates.filters.select'),
            $row->client?->fullname ?? '-',
            $types[$row->client?->type] ?? 'Unknown',
            $row->service?->name,
            trans('translates.work_destination.' . $row->destination) ?? 'Təyinat orqanı boşdur',
            trans('translates.work_status.' . $row->status),
            strip_tags($row->detail),
            implode(', ', $row->documents?->pluck('name')->toArray() ?? []),
            optional($row->paid_at)?->format('d/m/Y') ?? 'Tam Ödəniş olmayıb',
            optional($row->vat_date)?->format('d/m/Y') ?? 'ƏDV Ödənişi olmayıb',
            $row->getParameter($row::VAT) + $row->getParameter($row::AMOUNT) + $row->getParameter($row::ILLEGALAMOUNT),
            $row->getParameter($row::PAID) + $row->getParameter($row::VATPAYMENT) + $row->getParameter($row::ILLEGALPAID),
            ($row->getParameter($row::VAT) + $row->getParameter($row::AMOUNT) + $row->getParameter($row::ILLEGALAMOUNT)
                - ($row->getParameter($row::PAID) + $row->getParameter($row::VATPAYMENT) +  $row->getParameter($row::ILLEGALPAID))) * -1,
            optional($row->created_at)?->toDateString(), optional($row->created_at)?->toTimeString(),
            optional($row->injected_at)?->toDateString(), optional($row->injected_at)?->toTimeString(),
            $row->total_amount ?? 'Məlumat yoxdur',
            trans('translates.payment_methods.' . $row->payment_method),
            $agentName,
            $reference?->name ?? 'Referans yoxdur',
            optional($row->updated_at)?->format('d/m/Y H:i:s'),
        ];

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
                'client.coordinators:id,name,surname,client_id',
                'user:id,name,surname',
                'service:id,name',
                'asanImza:id,user_with_company,work_id',
                'documents:id,work_id,name',
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
