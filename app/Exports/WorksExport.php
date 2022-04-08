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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
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
//            trans('translates.columns.created_by'),
//            trans('translates.fields.department'),
            trans('translates.columns.user'),
            trans('translates.navbar.asan_imza'),
            trans('translates.general.work_service'),
            'Şəxs',
            'Müştəri adı',
            'VOEN/GOOEN',
            'Status',
            'Qalıq',
        ];

        foreach (Service::serviceParameters() as $serviceParameter) {
            $this->headings[] = $serviceParameter['data']->getAttribute('label');
        }

        $this->headings = array_merge($this->headings, [
                trans('translates.columns.created_at'),
                'Bitirilib',
                'Təstiqlənib',
                'Təstiqlənib Saat',
            ]
        );
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        $maps = [
            $row->getRelationValue('user')->getAttribute('fullname'),
            $row->asanImza()->exists() ? $row->getRelationValue('asanImza')->getAttribute('user_with_company') : trans('translates.filters.select'),
            $row->getRelationValue('service')->getAttribute('shortName'),
            $row->getRelationValue('client')->getAttribute('type') ? 'FŞ' : 'HŞ',
            $row->getRelationValue('client')->getAttribute('fullname'),
            $row->getRelationValue('client')->getAttribute('voen') ?? 'Yoxdur',
            trans('translates.work_status.' . $row->status),
            $row->getParameter(32) - $row->getParameter(19)
        ];

        foreach (Service::serviceParameters() as $serviceParameter) {
            $maps[] = $row->getParameter($serviceParameter['data']->getAttribute('id'));
        }

        return array_merge($maps, [
            $row->getAttribute('created_at')->format('d-m-Y'),
            optional($row->getAttribute('datetime'))->format('d-m-Y') ?? 'Xeyir',
            optional($row->getAttribute('verified_at'))->format('d-m-Y') ?? 'Xeyir',
            optional($row->getAttribute('verified_at'))->format('H:i') ?? 'Xeyir'
        ]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Style the first row as bold text.
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

//    public function columnFormats(): array
//    {
//        return [
//            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
//            'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
//        ];
//    }
}
