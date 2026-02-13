<?php

namespace App\Exports;

use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TelegramWorksExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected Carbon $fromDate;
    protected Carbon $toDate;
    protected Collection $works;

    public function __construct(Carbon $fromDate, Carbon $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->loadWorks();
    }

    protected function loadWorks(): void
    {
        // SQL-dən adaptasiya - SQL-dəki kimi sorğu
        $sql = "
            SELECT
                w.id AS work_id,
                w.client_id,
                ANY_VALUE(c.fullname) AS client_name,
                ANY_VALUE(JSON_UNQUOTE(JSON_EXTRACT(c.deleted_items, '$.phone3'))) AS phone3,
                ANY_VALUE(JSON_UNQUOTE(JSON_EXTRACT(c.deleted_items, '$.phone2'))) AS phone2,
                ANY_VALUE(c.phone1) AS phone1,
                ANY_VALUE(c.voen) AS voen,
                ANY_VALUE(JSON_UNQUOTE(JSON_EXTRACT(d.name, '$.az'))) AS department_name,
                ANY_VALUE(JSON_UNQUOTE(JSON_EXTRACT(s.name, '$.az'))) AS service_name,
                ANY_VALUE(s.detail) AS service_detail,
                ANY_VALUE(w.created_at) AS created_at,
                ANY_VALUE(w.invoiced_date) AS invoice_date,
                ANY_VALUE(w.paid_at) AS paid_at,
                ANY_VALUE(w.vat_date) AS vat_date,
                ANY_VALUE(w.code) AS code,
                ANY_VALUE(w.status) AS status,
                ANY_VALUE(w.destination) AS destination,
                ANY_VALUE(asan_user.name) AS asan_imza_user_name,
                ANY_VALUE(asan_company.name) AS asan_imza_company_name,
                MAX(CASE WHEN wp.parameter_id = 17 THEN wp.value END) AS gb,
                MAX(CASE WHEN wp.parameter_id = 18 THEN wp.value END) AS code_param,
                MAX(CASE WHEN wp.parameter_id = 20 THEN wp.value END) AS xidmet,
                MAX(CASE WHEN wp.parameter_id = 33 THEN wp.value END) AS mebleg,
                MAX(CASE WHEN wp.parameter_id = 34 THEN wp.value END) AS edv,
                MAX(CASE WHEN wp.parameter_id = 35 THEN wp.value END) AS esas_mebleg_odenilen,
                MAX(CASE WHEN wp.parameter_id = 36 THEN wp.value END) AS edv_odenilen,
                MAX(CASE WHEN wp.parameter_id = 37 THEN wp.value END) AS diger_odenis,
                MAX(CASE WHEN wp.parameter_id = 38 THEN wp.value END) AS diger_mebleg,
                MAX(CASE WHEN wp.parameter_id = 50 THEN wp.value END) AS qeyri_rəsmi_mebleg,
                MAX(CASE WHEN wp.parameter_id = 55 THEN wp.value END) AS umumi_odenis,
                MAX(CASE WHEN wp.parameter_id = 19 THEN wp.value END) AS service_count
            FROM works w
            JOIN clients c ON c.id = w.client_id
            LEFT JOIN departments d ON w.department_id = d.id
            LEFT JOIN services s ON w.service_id = s.id
            LEFT JOIN work_parameter wp ON wp.work_id = w.id
            LEFT JOIN asan_imzalar ai ON w.asan_imza_id = ai.id
            LEFT JOIN users asan_user ON ai.user_id = asan_user.id
            LEFT JOIN companies asan_company ON ai.company_id = asan_company.id
            WHERE w.deleted_at IS NULL
              AND c.deleted_at IS NULL
              AND w.created_at >= ?
              AND w.created_at < ?
            GROUP BY w.id
            ORDER BY w.created_at DESC
        ";

        $this->works = collect(DB::select($sql, [
            $this->fromDate->startOfDay(),
            $this->toDate->endOfDay(),
        ]));
    }

    public function collection()
    {
        return $this->works;
    }

    public function headings(): array
    {
        return [
            'İş ID',
            'Kod',
            'Müştəri Adı',
            'VÖEN',
            'Telefon 1',
            'Telefon 2',
            'Telefon 3',
            'Xidmət Adı',
            'Xidmət Detalları',
            'Şöbə',
            'Status',
            'İstifadəçi',
            'Yaradılma Tarixi',
            'Faktura Tarixi',
            'Ödəniş Tarixi',
            'ƏDV Tarixi',
            'Asan İmza İstifadəçi',
            'Asan İmza Şirkət',
            'Destination',
            'Məbləğ',
            'ƏDV',
            'Ödənilmiş Məbləğ',
            'Ödənilmiş ƏDV',
            'Digər Ödəniş',
            'Digər Məbləğ',
            'Qeyri-rəsmi Məbləğ',
            'Ümumi Ödəniş',
        ];
    }

    public function map($work): array
    {
        // $work artıq stdClass obyektidir (DB::select-dən gəlir)
        $work = (object) $work;

        // Parameter dəyərləri (artıq SQL-dən gəlib)
        $mebleg = is_numeric($work->mebleg) ? (float) $work->mebleg : 0;
        $edv = is_numeric($work->edv) ? (float) $work->edv : 0;
        $esas_mebleg_odenilen = is_numeric($work->esas_mebleg_odenilen) ? (float) $work->esas_mebleg_odenilen : 0;
        $edv_odenilen = is_numeric($work->edv_odenilen) ? (float) $work->edv_odenilen : 0;
        $diger_odenis = is_numeric($work->diger_odenis) ? (float) $work->diger_odenis : 0;
        $diger_mebleg = is_numeric($work->diger_mebleg) ? (float) $work->diger_mebleg : 0;
        $qeyri_rəsmi_mebleg = is_numeric($work->qeyri_rəsmi_mebleg) ? (float) $work->qeyri_rəsmi_mebleg : 0;
        $umumi_odenis = is_numeric($work->umumi_odenis) ? (float) $work->umumi_odenis : 0;

        // Destination adı
        $destinationNames = [
            '1' => '14000 Aksizli mallar üzrə BGİ',
            '2' => '001100 Bakı BGİ',
            '3' => '008000 HNBGİ',
            '4' => '001180 Xocəsən g/p',
            '5' => '001000 Sumqayıt Gİ',
            '6' => '13000 Dəniz nəqliyyatı və Enerji resursları BGİ',
            '7' => '002000 Balakən Gİ',
            '8' => '034800 Şirvan g/p',
            '9' => '012000 Biləsuvar Gİ',
            '10' => '009000 Gəncə Gİ',
            '11' => '014200 Xaçmaz Gİ',
            '12' => '012100 Xudafərin G/P',
            '13' => '009005 Yevlax G/P',
            '14' => '008100 Naxçıvan BGİ',
            '15' => '001203 Bakı KOB g/p',
            '16' => '001180 Sahil g/p',
            '17' => '008088 Poçt göndərişləri G/P',
            '18' => '008021 Abşeron G/P',
            '19' => '008080 HNBGİ (Aksiz)',
        ];
        $destinationName = $destinationNames[$work->destination] ?? ($work->destination ?? '-');

        // Status adı
        $statusNames = [
            Work::PLANNED => 'Planlaşdırılmış',
            Work::PENDING => 'Gözləyən',
            Work::STARTED => 'Başlanmış',
            Work::INJECTED => 'Təsdiqlənmiş',
            Work::RETURNED => 'Qaytarılmış',
            Work::ARCHIVE => 'Arxiv',
            Work::DONE => 'Tamamlanmış',
            Work::REJECTED => 'Rədd edilmiş',
        ];
        $statusName = $statusNames[$work->status] ?? 'Naməlum';

        // Tarixlər
        $createdAt = $work->created_at ? Carbon::parse($work->created_at)->format('Y-m-d H:i:s') : '-';
        $invoiceDate = $work->invoice_date ? Carbon::parse($work->invoice_date)->format('Y-m-d') : '-';
        $paidAt = $work->paid_at ? Carbon::parse($work->paid_at)->format('Y-m-d') : '-';
        $vatDate = $work->vat_date ? Carbon::parse($work->vat_date)->format('Y-m-d') : '-';

        return [
            $work->work_id,
            $work->code ?? '-',
            $work->client_name ?? '-',
            $work->voen ?? '-',
            $work->phone1 ?? '-',
            $work->phone2 ?? '-',
            $work->phone3 ?? '-',
            $work->service_name ?? '-',
            $work->service_detail ?? '-',
            $work->department_name ?? '-',
            $statusName,
            '-', // User (SQL-də yoxdur, lazım olsa əlavə edilə bilər)
            $createdAt,
            $invoiceDate,
            $paidAt,
            $vatDate,
            $work->asan_imza_user_name ?? '-',
            $work->asan_imza_company_name ?? '-',
            $destinationName,
            number_format($mebleg, 2, '.', ''),
            number_format($edv, 2, '.', ''),
            number_format($esas_mebleg_odenilen, 2, '.', ''),
            number_format($edv_odenilen, 2, '.', ''),
            number_format($diger_odenis, 2, '.', ''),
            number_format($diger_mebleg, 2, '.', ''),
            number_format($qeyri_rəsmi_mebleg, 2, '.', ''),
            number_format($umumi_odenis, 2, '.', ''),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
