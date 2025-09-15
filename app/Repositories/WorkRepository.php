<?php

namespace App\Repositories;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Work;
use Carbon\Carbon;

class WorkRepository implements WorkRepositoryInterface
{
    public function allFilteredWorks(array $filters = [], $dateFilters = [])
    {
        $user = auth()->user();

        // YORUM: UI-dan gələn əsas idarə sahələri
        $needAttention = (bool)($filters['need_attention'] ?? false); // YORUM: Need Attention checkbox
        $orderBy       = $filters['order_by']        ?? null;         // YORUM: Sıralama açarı (məs: 'need_attention')
        $orderDir      = strtolower($filters['order_dir'] ?? 'desc'); // YORUM: 'asc' | 'desc'
        $limit         = (int)($filters['limit']     ?? 25);          // YORUM: Səhifələmə limiti

        // Tarix filterləri üçün explode et
        $dateRanges = [];
        foreach (['datetime', 'created_at', 'injected_at', 'entry_date', 'invoiced_date', 'vat_date', 'paid_at'] as $dateField) {
            if (!empty($filters[$dateField])) {
                $dateRanges[$dateField] = explode(' - ', $filters[$dateField]);
            }
        }

        $status   = $filters['status']   ?? null;
        $statuses = $filters['statuses'] ?? [];

        $query = Work::query()
            ->select('works.*')
            ->with([
                'creator',
                'department:id,name,short_name',
                'service',
                'user:id,name,surname,department_id,permissions',
                'client:id,fullname,voen',
                'asanImza:id,user_id,company_id'
            ])
            ->when(!empty($status), fn($q) => $q->where('status', $status))
            ->when(!empty($statuses), fn($q) => $q->whereNotIn('status', $statuses))
            ->when(Work::userCannotViewAll(), function ($query) use ($user) {
                if ($user->hasPermission('viewAllDepartment-work')) {
                    $query->where('department_id', $user->getAttribute('department_id'));
                } else {
                    $query->where(function ($q) use ($user) {
                        $q->whereNull('user_id')->where('department_id', $user->getAttribute('department_id'));
                    })->orWhere('user_id', $user->getAttribute('id'));
                }
            })
            ->where(function ($query) use ($filters, $dateFilters, $dateRanges) {

                foreach ($filters as $column => $value) {
                    // YORUM: Bu sahələri loop-da atlayırıq — aşağıda ayrıca idarə edirik
                    if (in_array($column, ['limit', 'need_attention', 'order_by', 'order_dir'])) {
                        continue; // YORUM: skip
                    }

                    $query->when($value, function ($query, $value) use ($column, $dateFilters, $dateRanges) {

                        if ($column == 'verified_at') {
                            // YORUM: verified_at = 1 → NULL (yoxlanmamış), 2 → NOT NULL (yoxlanmış)
                            $value == 1
                                ? $query->whereNull($column)
                                : $query->whereNotNull($column);

                        } elseif ($column == 'paid_at' && in_array($value, [1, 2])) {
                            // YORUM: paid_at = 1 → ödənməmiş (NULL), 2 → ödənmiş (NOT NULL)
                            $value == 1
                                ? $query->whereNull($column)
                                : $query->whereNotNull($column);

                        } elseif ($column == 'asan_imza_company_id') {
                            // YORUM: Asan İmza şirkətinə görə filter
                            $query->whereHas('asanImza', function ($q) use ($value) {
                                $q->whereHas('company', fn($c) => $c->whereId($value));
                            });

                        } elseif (is_string($value) && isset($dateFilters[$column]) && $dateFilters[$column]) {
                            // YORUM: Tarix aralığı filteri: "YYYY-MM-DD - YYYY-MM-DD"
                            if (isset($dateRanges[$column])) {
                                $query->whereBetween($column, [
                                    Carbon::parse($dateRanges[$column][0])->startOfDay(),
                                    Carbon::parse($dateRanges[$column][1])->endOfDay(),
                                ]);
                            }

                        } elseif (in_array($column, ['code', 'declaration_no', 'transport_no'], true)) {
                            // YORUM: Text axtarış sahələri
                            $query->where($column, 'LIKE', "%$value%");

                        } elseif ($column == 'service_id') {
                            // YORUM: Bir neçə service_id gələ bilər
                            $query->whereIn($column, (array)$value);

                        } elseif ($column == 'destination') {
                            $query->where($column, $value);

                        } elseif (is_numeric($value)) {
                            // YORUM: Digər ədədi sütunlar üçün bərabərlik
                            $query->where($column, $value);
                        }
                    });
                }
            });

        // ===========================
        // YORUM: NEED ATTENTION FILTER
        // Şərt: paid_at IS NULL, invoiced_date NOT NULL, invoiced_date <= now() - 30 gün
        // ===========================
        if ($needAttention) {
            // Əgər modeldə scopeNeedsAttention() yazmısansa:
            // $query->needsAttention();

            // Scope yoxdursa, birbaşa SQL şərti:
            $query->whereNull('paid_at')
                ->whereNotNull('invoiced_date')
                ->where('invoiced_date', '<=', now()->subDays(30));
        }

        // ===========================
        // YORUM: ORDER BY — NEED ATTENTION
        // CASE ilə computed sahəyə görə sıralama
        // ===========================
        if ($orderBy === 'need_attention') {
            $expr = "CASE 
                        WHEN paid_at IS NULL 
                         AND invoiced_date IS NOT NULL 
                         AND invoiced_date <= ? 
                        THEN 1 ELSE 0 
                     END";
            // YORUM: DESC → əvvəlcə “diqqət tələb edənlər”
            $query->orderByRaw("$expr " . ($orderDir === 'asc' ? 'ASC' : 'DESC'), [now()->subDays(30)]);
        } else {
            // YORUM: Default sıralama
            $query->orderByDesc('created_at')
                ->orderByDesc('id');
        }

        // YORUM: Səhifələnmiş nəticə qaytar
        return $query->paginate($limit);
    }
}
