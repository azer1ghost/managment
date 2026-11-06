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
        {--client= : Yalnız bu müştərinin işləri}
        {--from= : Bu tarixdən (created_at, Y-m-d) sonrakı işlər}';

    protected $description = 'Məbləği boş olan işlərə müştəri qiymətinə görə məbləğ və ƏDV yazır';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

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

        $total = (clone $query)->count();
        $this->info(($dryRun ? '[DRY RUN] ' : '') . "Məbləği boş iş: {$total}");

        $filled = 0;
        $withVat = 0;
        $skipped = ['no_price' => 0, 'no_quantity' => 0];
        $sample = [];
        $sources = [];

        $query->orderBy('id')->chunkById(500, function ($works) use ($dryRun, &$filled, &$withVat, &$skipped, &$sample, &$sources) {
            foreach ($works as $work) {
                $result = $work->calculateAmountAndVat();

                if ($result === null) {
                    $skipped[Work::getClientServiceAmount($work) !== null ? 'no_quantity' : 'no_price']++;
                    continue;
                }

                [$amount, $vat] = $result;

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

        return self::SUCCESS;
    }
}
