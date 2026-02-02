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
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorksExport extends DefaultValueBinder implements FromQuery, WithMapping, WithHeadings, WithColumnWidths, WithColumnFormatting, WithCustomValueBinder, ShouldAutoSize, WithStyles, WithChunkReading
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
            'VÖEN/GÖEN',
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
            'Tam məbləğ (Əsas + Digər)',
            'Faktiki məbləğ (Əsas + ƏDV + Digər)',
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
        $esasMebleg      = $row->getParameter(33) ?? 0;
        $say             = $row->getParameter(20);
        $edv             = $row->getParameter(34) ?? 0;
        $diger           = $row->getParameter(38) ?? 0;
        $esasVereq        = $row->getParameter(48);

        $esasPaid        = $row->getParameter(35) ?? 0;
        $edvPaid         = $row->getParameter(36) ?? 0;
        $digerPaid       = $row->getParameter(37) ?? 0;

        // Correct calculations according to new requirements
        // Total Amount = Main Amount (AMOUNT) + Other Amount (ILLEGALAMOUNT)
        $tamMebleg = $esasMebleg + $diger;
        
        // Actual Amount = Main Amount (AMOUNT) + VAT (VAT) + Other Amount (ILLEGALAMOUNT)
        $faktikiMebleg = $esasMebleg + $edv + $diger;
        
        // Total paid amount
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
            $row->client?->voen ?? '-',

            $row->service?->name ?? '-',
            trans('translates.work_status.' . $row->status),
            trans('translates.work_destination.' . $row->destination),

            strip_tags($row->detail),

            $gb,
            $kodSayi,
            $say,
            $esasVereq,
            (float) $esasMebleg,
            (float) $edv,
            (float) $diger,
            (float) $tamMebleg,
            (float) $faktikiMebleg,
            optional($row->invoiced_date)?->toDateString(),
            $row->code,

            (float) $esasPaid,
            optional($row->paid_at)?->format('d/m/Y'),
            (float) $edvPaid,
            optional($row->vat_date)?->format('d/m/Y'),
            (float) $digerPaid,
            (float) $odenmis,
            trans('translates.payment_methods.' . $row->payment_method),

            (float) $borcEsas,
            (float) $borcEdv,
            (float) $borcUmumi,

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
                'client:id,fullname,type,voen',
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

    /**
     * Məbləğ sütunlarına number format tətbiq et
     */
    public function columnFormats(): array
    {
        return [
            // Məbləğ sütunları (24-28: Əsas Məbləğ, ƏDV, Digər, Tam, Faktiki) — VÖEN/GÖEN əlavə olunduqdan sonra +1 sürüşdü
            'X' => NumberFormat::FORMAT_NUMBER_00,  // 24: Əsas Məbləğ
            'Y' => NumberFormat::FORMAT_NUMBER_00,  // 25: ƏDV
            'Z' => NumberFormat::FORMAT_NUMBER_00,  // 26: Digər məbləğ
            'AA' => NumberFormat::FORMAT_NUMBER_00, // 27: Tam məbləğ
            'AB' => NumberFormat::FORMAT_NUMBER_00, // 28: Faktiki məbləğ
            // Ödəniş sütunları (31, 33, 35, 36: Əsas Ödənilən, ƏDV Ödənilən, Digər Ödəniş, Faktiki ödəniş)
            'AE' => NumberFormat::FORMAT_NUMBER_00, // 31: Əsas Məbləğdən Ödənilən
            'AG' => NumberFormat::FORMAT_NUMBER_00, // 33: ƏDV'dən Ödənilən
            'AI' => NumberFormat::FORMAT_NUMBER_00, // 35: Digər Ödəniş
            'AJ' => NumberFormat::FORMAT_NUMBER_00, // 36: Faktiki ödəniş
            // Borc sütunları (38-40: Borc əsas, Borc ƏDV, Borc ümumi)
            'AL' => NumberFormat::FORMAT_NUMBER_00, // 38: Borc əsas
            'AM' => NumberFormat::FORMAT_NUMBER_00, // 39: Borc ƏDV
            'AN' => NumberFormat::FORMAT_NUMBER_00, // 40: Borc ümumi
        ];
    }

    /**
     * Məbləğ sütunlarını mütləq numeric olaraq yaz
     */
    public function bindValue(Cell $cell, $value)
    {
        // Məbləğ sütunları: X, Y, Z, AA, AB, AE, AG, AI, AJ, AL, AM, AN (VÖEN/GÖEN əlavə olunduqdan sonra sürüşdü)
        $amountColumns = ['X', 'Y', 'Z', 'AA', 'AB', 'AE', 'AG', 'AI', 'AJ', 'AL', 'AM', 'AN'];
        
        if (in_array($cell->getColumn(), $amountColumns, true)) {
            // Əgər string formatlanmış məbləğdirsə (boşluq və ya vergül ilə), təmizlə
            if (is_string($value)) {
                $cleanedValue = str_replace([' ', ','], ['', ''], $value);
            } else {
                $cleanedValue = $value;
            }
            
            // Numeric yoxla və float kimi yaz
            if (is_numeric($cleanedValue) || $cleanedValue === '' || $cleanedValue === null) {
                $numericValue = ($cleanedValue === '' || $cleanedValue === null) ? 0 : (float) $cleanedValue;
                $cell->setValueExplicit($numericValue, DataType::TYPE_NUMERIC);
                return true;
            }
        }
        
        return parent::bindValue($cell, $value);
    }
}
