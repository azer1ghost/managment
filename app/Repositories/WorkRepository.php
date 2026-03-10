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

        // Tarix filterləri üçün explode et
        $dateRanges = [];
        foreach (['datetime', 'created_at', 'injected_at', 'entry_date', 'invoiced_date', 'vat_date', 'paid_at'] as $dateField) {
            if (!empty($filters[$dateField])) {
                $dateRanges[$dateField] = explode(' - ', $filters[$dateField]);
            }
        }

        $status = $filters['status'] ?? null;
        $statuses = $filters['statuses'] ?? [];

        return Work::query()
            ->select('works.*')
            ->with([
                'creator', 'department:id,name,short_name',
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
                    if (in_array($column, ['limit', 'zero_amount', 'statuses', 'status'])) continue;

                    $query->when($value, function ($query, $value) use ($column, $dateFilters, $dateRanges) {
                        if ($column == 'verified_at') {
                            $value == 1
                                ? $query->whereNull($column)
                                : $query->whereNotNull($column);
                        }
                        elseif ($column == 'paid_at' && in_array($value, [1, 2])) {
                            $value == 1
                                ? $query->whereNull($column)
                                : $query->whereNotNull($column);
                        } elseif ($column == 'asan_imza_company_id') {
                            $query->whereHas('asanImza', function ($q) use ($value) {
                                $q->whereHas('company', fn($c) => $c->whereId($value));
                            });
                        } elseif (is_string($value) && isset($dateFilters[$column]) && $dateFilters[$column]) {
                            if (isset($dateRanges[$column])) {
                                $query->whereBetween($column, [
                                    Carbon::parse($dateRanges[$column][0])->startOfDay(),
                                    Carbon::parse($dateRanges[$column][1])->endOfDay(),
                                ]);
                            }
                        } elseif ($column == 'code' || $column == 'declaration_no' || $column == 'transport_no') {
                            $query->where($column, 'LIKE', "%$value%");
                        } elseif ($column == 'service_id') {
                            $query->whereIn($column, (array)$value);
                        } elseif ($column == 'destination') {
                            $query->where($column, $value);
                        } elseif (is_numeric($value)) {
                            $query->where($column, $value);
                        }
                    });
                }
            })
            ->when(!empty($filters['zero_amount']), function ($query) {
                // Məbləği 0 olan işlər: parametr ID 19,33,34,35,36,37,38
                // üçün heç bir müsbət dəyər olmayan işləri tapır
                $query->whereDoesntHave('parameters', function ($q) {
                    $q->whereIn('parameter_id', [19, 33, 34, 35, 36, 37, 38])
                        ->whereNotNull('value')
                        ->where('value', '!=', '')
                        ->where('value', '!=', '0');
                });
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }
}

