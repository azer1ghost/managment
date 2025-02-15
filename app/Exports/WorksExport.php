<?php

namespace App\Exports;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Service;
use App\Models\CustomerEngagement;
use App\Models\User;
use App\Models\Partner;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorksExport implements FromQuery, WithMapping, WithHeadings, WithColumnWidths, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected array $filters = [],$dateRanges = [],$dateFilters = [];
    protected WorkRepositoryInterface $workRepository;
    protected array $headings = [];

    public function __construct(WorkRepositoryInterface $workRepository, array $filters = [], array $dateFilters = [])
    {
        $this->workRepository = $workRepository;
        $this->filters = $filters;
        $this->dateFilters = $dateFilters;

        $this->headings = [
            'Sorğu nömrəsi',
            'İş Kodu',
            'Yaradan şəxs',
            'Şöbə',
            'Koordinator',
            'Müştəri adı',
            'Şəxs',
            'Xidmət',
            'Təyinat Orqanı',
            'Status',
            'Sənədlər',
            'GB Sayı',
            'Kod Sayı',
            'Əsas Məbləğ Ödəniş Tarixi',
            'ƏDV Ödəniş Tarixi',
            'Qalıq',
            'Yaradılma Tarixi',
            'Son Dəyişiklik Tarixi',
            'Sisteme Vurulma Tarixi',
            'Toplam Məbləğ',
            'Vasitəçi',
            'Referans',
        ];

        foreach (Service::serviceParametersExport() as $servicesParameter) {
            $this->headings[] = $servicesParameter['data']->getAttribute('label');
        }
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        $customerEngagement = CustomerEngagement::where('client_id', $row->client_id)->first();
        $agent = $customerEngagement ? User::find($customerEngagement->user_id) : null;
        $reference = $customerEngagement ? Partner::find($customerEngagement->partner_id) : null;

        $maps = [
            $row->getAttribute('declaration_no'),
            $row->getAttribute('code'),
            $row->getRelationValue('creator')->getAttribute('fullname_with_position'),
            $row->getRelationValue('department')->getAttribute('short'),
            optional($row->getRelationValue('client')->coordinators->first())->fullname ?? 'Koordinator Yoxdur',
            $row->getRelationValue('client')->getAttribute('fullname'),
            $row->getRelationValue('client')->getAttribute('type') ? 'FŞ' : 'HŞ',
            $row->getRelationValue('service')->getAttribute('name'),
            trans('translates.work_destination.' . $row->getAttribute('destination')) ?? 'Təyinat orqanı boşdur',
            trans('translates.work_status.' . $row->getAttribute('status')),
            implode(', ', $row->documents->pluck('name')->toArray()),
            $row->getParameter($row::GB),
            $row->getParameter($row::CODE),
            optional($row->getAttribute('paid_at'))->format('d-m-Y') ?? 'Tam Ödəniş olmayıb',
            optional($row->getAttribute('vat_date'))->format('d-m-Y') ?? 'ƏDV Ödənişi olmayıb',
            ($row->getParameter($row::VAT) + $row->getParameter($row::AMOUNT) + $row->getParameter($row::ILLEGALAMOUNT) - ($row->getParameter($row::PAID) + $row->getParameter($row::VATPAYMENT) +  $row->getParameter($row::ILLEGALPAID))) * -1,
            optional($row->getAttribute('created_at'))->format('d-m-Y H:i:s'),
            optional($row->getAttribute('updated_at'))->format('d-m-Y H:i:s'),
            optional($row->getAttribute('injected_at'))->format('d-m-Y H:i:s'),
            $row->getAttribute('total_amount') ?? 'Məlumat yoxdur',
            optional($agent)->getAttribute('fullname') ?? 'Vasitəçi yoxdur',
            optional($reference)->getAttribute('name') ?? 'Referans yoxdur',
        ];

        foreach (Service::serviceParametersExport() as $servicesParameter) {
            $maps[] = null;
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
        return $this->workRepository->allFilteredWorks($this->filters, $this->dateFilters);
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
}
