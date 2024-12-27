<?php

namespace App\Exports;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Service;
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

    protected array $filters = [], $dateRanges = [], $dateFilters = [];
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
            trans('translates.columns.created_at'),
            'Müştəri adı',
            'Şəxs',
            trans('translates.general.work_service'),
        ];

        foreach (Service::serviceParametersExport() as $servicesParameter) {
            $this->headings[] = $servicesParameter['data']->getAttribute('label');
        }

        $this->headings = array_merge($this->headings, [
            'Əsas Məbləğ Ödəniş Tarixi',
            'Ədv Ödəniş Tarixi',
            'Borc',
            'Bəyannaməçi',
            'Sistemdə (ASAN IMZA)',
            trans('translates.columns.department'),
            'Ödəniş Üsulu',
            'Bank Xərci',
        ]);
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        $maps = [
            $row->getAttribute('declaration_no'),
            $row->getAttribute('code'),
            $row->getAttribute('created_at')->format('d-m-Y'),
            $row->getRelationValue('client')->getAttribute('fullname'),
            $row->getRelationValue('client')->getAttribute('type') ? 'FŞ' : 'HŞ',
            $row->getRelationValue('service')->getAttribute('shortName')
        ];

        foreach (Service::serviceParametersExport() as $servicesParameter) {
            $maps[] = $row->getParameter($servicesParameter['data']->getAttribute('id'));
        }

        return array_merge($maps, [
            optional($row->getAttribute('paid_at'))->format('d-m-Y') ?? 'Tam Ödəniş olmayıb',
            optional($row->getAttribute('vat_date'))->format('d-m-Y') ?? 'ƏDV Ödənişi olmayıb',
            $this->calculateDebt($row),
            $row->getRelationValue('user')->getAttribute('fullname'),
            $row->asanImza()->exists() ? $row->getRelationValue('asanImza')->getAttribute('user_with_company') : trans('translates.filters.select'),
            $row->getRelationValue('department')->getAttribute('short'),
            $row->getAttribute('payment_method') ? trans('translates.payment_methods.' . $row->getAttribute('payment_method')) : '',
            $row->getAttribute('bank_charge')
        ]);
    }

    private function calculateDebt($row): float
    {
        $vat = $this->numeric($row->getParameter($row::VAT));
        $amount = $this->numeric($row->getParameter($row::AMOUNT));
        $illegalAmount = $this->numeric($row->getParameter($row::ILLEGALAMOUNT));
        $paid = $this->numeric($row->getParameter($row::PAID));
        $vatPayment = $this->numeric($row->getParameter($row::VATPAYMENT));
        $illegalPaid = $this->numeric($row->getParameter($row::ILLEGALPAID));

        return ($vat + $amount + $illegalAmount - ($paid + $vatPayment + $illegalPaid)) * -1;
    }

    private function numeric($value): float
    {
        return is_numeric($value) ? (float)$value : 0.0;
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
            'E' => 35,
            'F' => 13,
        ];
    }
}
