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
            'datetime' => explode(' - ', $filters['datetime']),
            'created_at' => explode(' - ', $filters['created_at']),
            'injected_at' => explode(' - ', $filters['injected_at']),
            'entry_date' => explode(' - ', $filters['entry_date']),
            'invoiced_date' => explode(' - ', $filters['invoiced_date']),
            'vat_date' => explode(' - ', $filters['vat_date']),
        ];
        $status = $filters['status'];
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
            ->when(!empty($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when(!empty($statuses), function ($query) use ($statuses) {
                $query->whereNotIn('status', $statuses);
            })
            ->when(Work::userCannotViewAll(), function ($query) use ($user){
                if ($user->hasPermission('viewAllDepartment-work')) {
                    $query->where('department_id', $user->getAttribute('department_id'));
                } else {
                    $query
                        ->where(function ($q) use ($user){
                            $q->whereNull('user_id')
                                ->where('department_id', $user->getAttribute('department_id'));
                        })
                        ->orWhere('user_id', $user->getAttribute('id'));
                }
            })
            ->where(function($query) use ($filters, $dateRanges, $dateFilters){
                foreach ($filters as $column => $value) {
                    if (in_array($column, ['limit', 'statuses']) || $value === null || $value === '') continue;

                    $query->when($value, function ($query, $value) use ($column, $dateRanges, $dateFilters) {
                        // Date range filters
                        if (isset($dateFilters[$column]) && isset($dateRanges[$column])) {
                            return $query->whereBetween($column, [
                                Carbon::parse($dateRanges[$column][0])->startOfDay(),
                                Carbon::parse($dateRanges[$column][1])->endOfDay()
                            ]);
                        }

                        // Null / Not Null filters
                        if (in_array($column, ['verified_at', 'paid_at'])) {
                            return $value == 1
                                ? $query->whereNull($column)
                                : $query->whereNotNull($column);
                        }

                        // LIKE filters
                        if (in_array($column, ['code', 'declaration_no'])) {
                            return $query->where($column, 'like', "%$value%");
                        }

                        // Relation filters
                        if ($column === 'asan_imza_company_id') {
                            return $query->whereHas('asanImza.company', function ($q) use ($value) {
                                $q->where('id', $value);
                            });
                        }

                        if ($column === 'asan_imza_id') {
                            return $query->whereHas('asanImza', function ($q) use ($value) {
                                $q->where('id', $value);
                            });
                        }

                        // Multiple select
                        if (is_array($value)) {
                            return $query->whereIn($column, $value);
                        }

                        // Default = match
                        return $query->where($column, $value);
                    });
                }
            })
            ->latest('id')
            ->latest('datetime');
    }
}
