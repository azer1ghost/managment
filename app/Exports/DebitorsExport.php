<?php

namespace App\Exports;

use App\Http\Controllers\Modules\DebitorController;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting as ExcelWithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DebitorsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, ExcelWithColumnFormatting
{
    use Exportable;

    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [
            'Qaimə Şirkəti',
            'VÖEN',
            'Müştəri',
            'Qaimə Kodu',
            'Qaimə Tarixi',
            'Qaimə Məbləği (Əsas)',
            'ƏDV',
            'Ödəniş Tarixi',
            'Ödənilmiş Məbləğ (Əsas)',
            'Ödəniş ƏDV',
            'Qalıq Məbləğ (Borc Əsas)',
            'Qalıq ƏDV',
            'Ödəniş Üsulu',
            'Vəziyyət',
        ];
    }

    public function collection()
    {
        $rows = (new DebitorController)->buildRows($this->filters);

        if (!empty($this->filters['debitor_status'])) {
            $rows = $rows->filter(fn($r) => $r->veziyyet === $this->filters['debitor_status'])->values();
        }

        return $rows;
    }

    public function map($row): array
    {
        $paymentMethods = trans('translates.payment_methods');
        $pmLabel = $paymentMethods[$row->payment_method] ?? '-';

        return [
            $row->invoice_company_name ?? '-',
            $row->voen ?? '-',
            $row->client_name ?? '-',
            $row->code ?? '-',
            $row->invoiced_date ? \Carbon\Carbon::parse($row->invoiced_date)->format('d.m.Y') : '-',
            $row->amount,
            $row->vat,
            $row->paid_at ? \Carbon\Carbon::parse($row->paid_at)->format('d.m.Y') : '-',
            $row->paid,
            $row->vat_payment,
            $row->qaliq,
            $row->qaliq_edv,
            $pmLabel,
            $row->veziyyet,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW],
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
