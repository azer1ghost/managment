<?php

namespace App\Console\Commands;

use App\Models\Work;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Məbləği boş olan işlərə müştərinin şöbə/xidmət qiymətinə və GB/xidmət sayına görə
 * məbləğ, asan imzanın şirkətinə görə ƏDV yazır.
 */
class FillEmptyWorkAmounts extends Command
{
    protected $signature = 'works:fill-empty-amounts
        {--dry-run : Heç nə yazmadan nəticəni göstər}
        {--include-zero : Məbləği 0 olan işləri də yenidən hesabla}
        {--skip-zero : Hesablanan məbləğ 0 çıxırsa yazma (müştəri qiyməti 0)}
        {--client= : Yalnız bu müştərinin işləri}
        {--from= : Bu tarixdən (created_at, Y-m-d) sonrakı işlər}
        {--to= : Bu tarixə qədər (created_at, Y-m-d, daxil olmaqla)}
        {--work= : Tək işi ətraflı yoxla (dry-run olmadan həm də yazır)}';

    protected $description = 'Məbləği boş olan işlərə müştəri qiymətinə görə məbləğ və ƏDV yazır';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($workId = $this->option('work')) {
            return $this->diagnose((int) $workId, $dryRun);
        }

        $serviceIds = array_merge(Work::COUNT_SERVICES, Work::GB_SERVICES, Work::QIB_SERVICES);

        $includeZero = (bool) $this->option('include-zero');

        $query = Work::query()
            ->whereNotNull('client_id')
            ->whereIn('service_id', $serviceIds)
            // Məbləğ parametri ya yoxdur, ya da boşdur
            ->whereNotExists(function ($q) use ($includeZero) {
                $q->select(DB::raw(1))
                    ->from('work_parameter')
                    ->whereColumn('work_parameter.work_id', 'works.id')
                    ->where('work_parameter.parameter_id', Work::AMOUNT)
                    ->whereNotNull('work_parameter.value')
                    ->where('work_parameter.value', '<>', '')
                    ->when($includeZero, fn($q) => $q->whereRaw("CAST(REPLACE(work_parameter.value, ',', '.') AS DECIMAL(14,2)) <> 0"));
            })
            ->with(['client', 'asanImza', 'department']);

        if ($client = $this->option('client')) {
            $query->where('client_id', $client);
        }
        if ($from = $this->option('from')) {
            $query->where('created_at', '>=', $from);
        }
        if ($to = $this->option('to')) {
            $query->where('created_at', '<', \Carbon\Carbon::parse($to)->addDay()->toDateString());
        }

        $total = (clone $query)->count();
        $this->info(($dryRun ? '[DRY RUN] ' : '') . "Məbləği boş iş: {$total}");

        $filled = 0;
        $withVat = 0;
        $skipped = ['no_price' => 0, 'no_quantity' => 0, 'zero' => 0];
        $skipZero = (bool) $this->option('skip-zero');
        $sample = [];
        $sources = [];

        $query->orderBy('id')->chunkById(500, function ($works) use ($dryRun, &$filled, &$withVat, &$skipped, &$sample, &$sources, $skipZero) {
            foreach ($works as $work) {
                $result = $work->calculateAmountAndVat();

                if ($result === null) {
                    $skipped[Work::getClientServiceAmount($work) !== null ? 'no_quantity' : 'no_price']++;
                    continue;
                }

                [$amount, $vat] = $result;

                if ($skipZero && $amount == 0) {
                    $skipped['zero']++;
                    continue;
                }

                // 0 olan iş yenə 0 çıxırsa (məs. 22 nömrəli asan imza) toxunmuruq
                if ($amount == 0 && $work->getParameter(Work::AMOUNT) !== null) {
                    continue;
                }

                if (!$dryRun) {
                    DB::transaction(function () use ($work, $amount, $vat) {
                        $work->setParameterValue(Work::AMOUNT, $amount);
                        $work->setParameterValue(Work::VAT, $vat);
                    });
                }

                [$price, $source] = Work::getClientServicePrice($work);
                $sources[$source] = ($sources[$source] ?? 0) + 1;
                $filled++;
                if ($vat > 0) {
                    $withVat++;
                }
                if (count($sample) < 20) {
                    $sample[] = [
                        $work->id,
                        $work->client_id,
                        $work->department_id,
                        $work->service_id,
                        "{$price} ({$source})",
                        $work->getParameter(Work::GB) ?? $work->getParameter(Work::SERVICECOUNT),
                        $work->asanImza?->company?->name,
                        $amount,
                        $vat,
                    ];
                }
            }
        });

        if ($sample) {
            $this->table(['İş', 'Müştəri', 'Şöbə', 'Xidmət', 'Vahid qiymət', 'GB/Say', 'Asan şirkəti', 'Məbləğ', 'ƏDV'], $sample);
        }

        $this->info(($dryRun ? 'Doldurulacaq' : 'Dolduruldu') . ": {$filled} (ƏDV-li: {$withVat}, ƏDV-siz: " . ($filled - $withVat) . ')');
        foreach ($sources as $source => $count) {
            $this->line("  qiymət mənbəyi — {$source}: {$count}");
        }
        $this->line("Ötürüldü — müştərinin bu xidmət/şöbə üçün qiyməti yoxdur: {$skipped['no_price']}");
        $this->line("Ötürüldü — GB / xidmət sayı daxil edilməyib: {$skipped['no_quantity']}");
        if ($skipZero) {
            $this->line("Ötürüldü — məbləğ 0 çıxır (müştəri qiyməti 0): {$skipped['zero']}");
        }

        return self::SUCCESS;
    }

    private function diagnose(int $workId, bool $dryRun): int
    {
        $work = Work::withTrashed()->with(['client', 'asanImza', 'department', 'service'])->find($workId);
        if (!$work) {
            $this->error("İş #{$workId} tapılmadı");
            return self::FAILURE;
        }

        $serviceId = (int) $work->service_id;
        $type = in_array($serviceId, Work::COUNT_SERVICES) ? 'xidmət sayı ilə'
            : (in_array($serviceId, Work::GB_SERVICES) ? 'GB + əsas vərəq ilə'
            : (in_array($serviceId, Work::QIB_SERVICES) ? 'GB + QİB əsas vərəq ilə' : 'HESABLANMAYAN XİDMƏT'));
        $client = $work->client;
        $deptId = (int) $work->department_id;

        $this->table(['Sahə', 'Dəyər'], [
            ['Silinib', $work->trashed() ? 'BƏLİ' : 'yox'],
            ['Müştəri', $work->client_id . ' — ' . ($client?->fullname ?? 'TAPILMADI')],
            ['Şöbə', $deptId . ' — ' . ($work->department?->name ?? '-')],
            ['Xidmət', $serviceId . ' — ' . ($work->service?->name ?? '-') . " ({$type})"],
            ['Asan imza', ($work->asan_imza_id ?? 'YOXDUR') . ' — şirkət: ' . ($work->asanImza?->company?->name ?? '-')
                . ' — ' . ($work->asanImza?->getKey() ? ($work->asanImza->hasNoVat() ? 'ƏDV-siz' : 'ƏDV-li') : 'ƏDV 0')],
            ['GB', var_export($work->getParameter(Work::GB), true)],
            ['Əsas vərəq sayı', var_export($work->getParameter(Work::MAINPAGE), true)],
            ['Xidmət sayı', var_export($work->getParameter(Work::SERVICECOUNT), true)],
            ['Hazırkı məbləğ', var_export($work->getParameter(Work::AMOUNT), true)],
            ['Hazırkı ƏDV', var_export($work->getParameter(Work::VAT), true)],
        ]);

        $rows = DB::table('client_service')->where('client_id', $work->client_id)->where('service_id', $serviceId)->get();
        $this->line('client_service (bu müştəri + xidmət): ' . ($rows->isEmpty() ? 'YOXDUR'
            : $rows->map(fn($r) => 'şöbə ' . ($r->department_id ?? 'ümumi') . ' = ' . var_export($r->amount, true))->implode(', ')));
        $allServices = DB::table('client_service')->where('client_id', $work->client_id)->pluck('service_id')->unique()->implode(', ');
        $this->line('Bu müştərinin qiyməti olan xidmətlər: ' . ($allServices ?: 'heç biri'));
        $this->line('clients.price = ' . var_export($client?->price, true)
            . ', main_paper = ' . var_export($client?->main_paper, true)
            . ', qibmain_paper = ' . var_export($client?->qibmain_paper, true));
        $deptPaper = $client ? $client->departmentPrice($deptId) : null;
        $this->line('Şöbə vərəq qiyməti: ' . ($deptPaper ? 'main_paper=' . var_export($deptPaper->main_paper, true) . ', qibmain_paper=' . var_export($deptPaper->qibmain_paper, true) : 'YOXDUR'));

        [$price, $source] = Work::getClientServicePrice($work);
        $this->line('Götürülən vahid qiymət: ' . ($price === null ? 'TAPILMADI' : "{$price} ({$source})"));

        $result = $work->calculateAmountAndVat();
        if ($result === null) {
            $this->error('Hesablamaq olmur: ' . ($price === null ? 'qiymət tapılmadı' : ($type === 'HESABLANMAYAN XİDMƏT' ? 'bu xidmət hesablama siyahısında deyil' : 'GB / xidmət sayı boşdur')));
            return self::FAILURE;
        }

        [$amount, $vat] = $result;
        $this->info("Hesablanan: məbləğ {$amount}, ƏDV {$vat}");

        if (!$dryRun) {
            $work->setParameterValue(Work::AMOUNT, $amount);
            $work->setParameterValue(Work::VAT, $vat);
            $this->info('Yazıldı.');
        }

        return self::SUCCESS;
    }
}
