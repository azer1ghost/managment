<?php

namespace App\Repositories;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Work;
use Carbon\Carbon;

class WorkRepository implements WorkRepositoryInterface {

    public function allFilteredWorks(array $filters = [], $dateFilters = [])
    {
        $user = auth()->user();

        $dateRanges = [
            'datetime' => $this->parseDateRange($filters['datetime'] ?? null),
            'created_at' => $this->parseDateRange($filters['created_at'] ?? null),
            'injected_at' => $this->parseDateRange($filters['injected_at'] ?? null),
            'entry_date' => $this->parseDateRange($filters['entry_date'] ?? null),
            'invoiced_date' => $this->parseDateRange($filters['invoiced_date'] ?? null),
            'vat_date' => $this->parseDateRange($filters['vat_date'] ?? null),
            'paid_at' => $this->parseDateRange($filters['paid_at'] ?? null),
        ];

        $status = $filters['status'] ?? null;
        $statuses = $filters['statuses'] ?? [];

        return Work::query()
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
                        $q->whereNull('user_id')
                            ->where('department_id', $user->getAttribute('department_id'));
                    })->orWhere('user_id', $user->getAttribute('id'));
                }
            })
            ->where(function ($query) use ($filters, $dateRanges, $dateFilters) {
                foreach ($filters as $column => $value) {
                    if ($column == 'limit') continue;

                    $query->when($value, function ($query, $value) use ($column, $dateRanges, $dateFilters) {
                        if (in_array($column, ['verified_at', 'paid_at'])) {
                            $value == 1
                                ? $query->whereNull($column)
                                : ($value == 2 ? $query->whereNotNull($column) : null);
                        } elseif ($column == 'asan_imza_company_id') {
                            $query->whereHas('asanImza', function ($q) use ($value) {
                                $q->whereHas('company', fn($c) => $c->whereId($value));
                            });
                        } elseif (in_array($column, ['code', 'declaration_no'])) {
                            $query->where($column, 'LIKE', "%$value%");
                        } elseif ($column === 'service_id') {
                            $query->whereIn($column, (array) $value);
                        } elseif ($column === 'destination') {
                            $query->where($column, $value);
                        } elseif (is_numeric($value)) {
                            $query->where($column, $value);
                        } elseif (is_string($value) && ($dateFilters[$column] ?? false) && $dateRanges[$column]) {
                            $query->whereBetween($column, $dateRanges[$column]);
                        }
                    });
                }
            })
            ->latest('id')
            ->latest('datetime');
    }

    private function parseDateRange($range)
    {
        if (!$range || !str_contains($range, ' - ')) {
            return null;
        }

        [$start, $end] = explode(' - ', $range);
        try {
            return [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay(),
            ];
        } catch (\Exception $e) {
            return null; // tarix parse olunmazsa null qaytar
        }
    }
}
