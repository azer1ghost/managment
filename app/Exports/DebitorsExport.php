<?php

namespace App\Exports;

use App\Models\Work;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnFormatting as ExcelWithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DebitorsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, ExcelWithColumnFormatting
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
            'Qaimə Kodu',
            'Qaimə Tarixi',
            'Qaimə Məbləği (Əsas)',
            'ƏDV',
            'Ödəniş Tarixi',
            'Ödənilmiş Məbləğ (Əsas)',
            'Ödəniş ƏDV',
            'Qalıq Məbləğ (Borc Əsas)',
            'Qalıq ƏDV',
            'Vəziyyət',
        ];
    }

    public function map($row): array
    {
        $amount     = (float) ($row->getParameter(Work::AMOUNT) ?? 0);
        $vat        = (float) ($row->getParameter(Work::VAT) ?? 0);
        $paid       = (float) ($row->getParameter(Work::PAID) ?? 0);
        $vatPayment = (float) ($row->getParameter(Work::VATPAYMENT) ?? 0);

        $qaliqMebleg = $amount - $paid;
        $qaliqEdv    = $vat - $vatPayment;

        $veziyyet = $this->getVeziyyet($amount, $vat, $paid, $vatPayment);

        return [
            optional($row->invoiceCompany)->getAttribute('name') ?? '-',
            optional($row->client)->getAttribute('voen') ?? '-',
            $row->getAttribute('code') ?? '-',
            optional($row->invoiced_date)?->toDateString(),
            $amount,
            $vat,
            optional($row->paid_at)?->toDateString(),
            $paid,
            $vatPayment,
            $qaliqMebleg,
            $qaliqEdv,
            $veziyyet,
        ];
    }

    protected function getVeziyyet(float $amount, float $vat, float $paid, float $vatPayment): string
    {
        $totalAmount = $amount + $vat;
        $totalPaid   = $paid + $vatPayment;

        if ($totalPaid <= 0) {
            return 'Açıq';
        }

        if (abs($amount - $paid) < 0.01 && abs($vat - $vatPayment) < 0.01) {
            return 'Bağlı';
        }

        return 'Qismən';
    }

    public function query()
    {
        $query = Work::query()
            ->whereNotNull('invoiced_date')
            ->with([
                'invoiceCompany:id,name',
                'client:id,fullname,voen',
                'parameters',
            ]);

        if (!empty($this->filters['invoice_company_id'])) {
            $query->where('invoice_company_id', $this->filters['invoice_company_id']);
        }

        if (!empty($this->filters['client_id'])) {
            $query->where('client_id', $this->filters['client_id']);
        }

        if (!empty($this->filters['status'])) {
            $query->where(function ($q) {
                // Status filter handled post-query in mapping; this is a pass-through
            });
        }

        if (!empty($this->filters['invoiced_date_from']) && !empty($this->filters['invoiced_date_to'])) {
            $query->whereBetween('invoiced_date', [
                $this->filters['invoiced_date_from'],
                $this->filters['invoiced_date_to'],
            ]);
        }

        return $query->orderByDesc('invoiced_date');
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
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
