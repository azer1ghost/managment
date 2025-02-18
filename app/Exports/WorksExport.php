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

            'Şöbə',
            'Koordinator',
            'İcraçı Əməkdaş',
            'Müştəri adı',
            'Şəxs (Fiziki / Hüquqi)',
            'Xidmət',
            'Təyinat Orqanı',
            'Status',
            'Sənədlər',
            'Əsas Məbləğ Ödəniş Tarixi',
            'ƏDV Ödəniş Tarixi',
            'Tam məbləğ',
            'Borc məbləğ',
            'Qalıq',
            'Yaradılma Tarixi (Gün)', 'Yaradılma Tarixi (Saat)',
            'Sisteme Tarixi (Gün)', 'Sisteme Tarixi (Saat)',
            'Toplam Məbləğ',
            'Vasitəçi',
            'Referans',
            'Son Dəyişiklik Tarixi',
        ];

        foreach (Service::serviceParametersExport() as $servicesParameter) {
            if (!in_array($servicesParameter['data']->getAttribute('id'), [51, 52, 53, 54, 56, 57, 60])) {
                $this->headings[] = $servicesParameter['data']->getAttribute('label');
            }
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

            $row->getRelationValue('department')->getAttribute('short'),
            optional($row->getRelationValue('client')->coordinators->first())->fullname ?? 'Koordinator Yoxdur',
            optional($row->getRelationValue('user'))->getAttribute('fullname') ?? 'İcraçı yoxdur',
            $row->getRelationValue('client')->getAttribute('fullname'),
            $row->getRelationValue('client')->getAttribute('type') == 0 ? 'HŞ' : 'FŞ',
            $row->getRelationValue('service')->getAttribute('name'),
            trans('translates.work_destination.' . $row->getAttribute('destination')) ?? 'Təyinat orqanı boşdur',
            trans('translates.work_status.' . $row->getAttribute('status')),
            implode(', ', $row->documents->pluck('name')->toArray()),
            optional($row->getAttribute('paid_at'))->format('d/m/Y') ?? 'Tam Ödəniş olmayıb',
            optional($row->getAttribute('vat_date'))->format('d/m/Y') ?? 'ƏDV Ödənişi olmayıb',
            ($row->getParameter($row::VAT) + $row->getParameter($row::AMOUNT) + $row->getParameter($row::ILLEGALAMOUNT))
            ($row->getParameter($row::PAID) + $row->getParameter($row::VATPAYMENT) + $row->getParameter($row::ILLEGALPAID))
            ($row->getParameter($row::VAT) + $row->getParameter($row::AMOUNT) + $row->getParameter($row::ILLEGALAMOUNT) - ($row->getParameter($row::PAID) + $row->getParameter($row::VATPAYMENT) +  $row->getParameter($row::ILLEGALPAID))) * -1,
            optional($row->getAttribute('created_at'))->format('d/m/Y'), optional($row->getAttribute('created_at'))->format('H:i:s'),
            optional($row->getAttribute('injected_at'))->format('d/m/Y'),optional($row->getAttribute('injected_at'))->format('H:i:s'),
            $row->getAttribute('total_amount') ?? 'Məlumat yoxdur',
            optional($agent)->getAttribute('fullname') ?? 'Vasitəçi yoxdur',
            optional($reference)->getAttribute('name') ?? 'Referans yoxdur',
            optional($row->getAttribute('updated_at'))->format('d/m/Y H:i:s'),
        ];

        foreach (Service::serviceParametersExport() as $servicesParameter) {
            if (!in_array($servicesParameter['data']->getAttribute('id'), [51, 52, 53, 54, 56, 57, 60])) {
                $maps[] = $row->getParameter($servicesParameter['data']->getAttribute('id'));
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
