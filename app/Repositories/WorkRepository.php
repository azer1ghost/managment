<?php

namespace App\Repositories;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Work;
use Carbon\Carbon;

class WorkRepository implements WorkRepositoryInterface
{
    public function allFilteredWorks(array $filters = [], array $dateFilters = []): \Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user();

        $dateRanges = [
            'datetime' => !empty($filters['datetime']) ? explode(' - ', $filters['datetime']) : null,
            'created_at' => !empty($filters['created_at']) ? explode(' - ', $filters['created_at']) : null,
            'injected_at' => !empty($filters['injected_at']) ? explode(' - ', $filters['injected_at']) : null,
            'entry_date' => !empty($filters['entry_date']) ? explode(' - ', $filters['entry_date']) : null,
            'invoiced_date' => !empty($filters['invoiced_date']) ? explode(' - ', $filters['invoiced_date']) : null,
            'vat_date' => !empty($filters['vat_date']) ? explode(' - ', $filters['vat_date']) : null,
        ];

        $status = $filters['status'] ?? null;
        $statuses = $filters['statuses'] ?? [];

        return Work::query()
            ->with([
                'creator',
                'department:id,name,short_name',
                'service',
                'user:id,name,surname,department_id,permissions',
                'client:id,fullname,voen',
                'asanImza:id,user_id,company_id',
            ])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($statuses, function ($query) use ($statuses) {
                $query->whereNotIn('status', $statuses);
            })
            ->when(Work::userCannotViewAll(), function ($query) use ($user) {
                if ($user->hasPermission('viewAllDepartment-work')) {
                    $query->where('department_id', $user->department_id);
                } else {
                    $query->where(function ($q) use ($user) {
                        $q->whereNull('user_id')
                            ->where('department_id', $user->department_id);
                    })->orWhere('user_id', $user->id);
                }
            })
            ->where(function ($query) use ($filters, $dateRanges) {
                foreach ($filters as $column => $value) {
                    if ($column === 'limit') {
                        continue;
                    }

                    $query->when($value, function ($query, $value) use ($column, $dateRanges) {
                        switch ($column) {
                            case 'verified_at':
                            case 'paid_at':
                                if ($value == 1) {
                                    $query->whereNull($column);
                                } elseif ($value == 2) {
                                    $query->whereNotNull($column);
                                }
                                break;
                            case 'asan_imza_company_id':
                                $query->whereHas('asanImza', function ($asanImzaQuery) use ($value) {
                                    $asanImzaQuery->whereHas('company', function ($companyQuery) use ($value) {
                                        $companyQuery->where('id', $value);
                                    });
                                });
                                break;
                            case 'code':
                            case 'declaration_no':
                                $query->where($column, 'LIKE', "%$value%");
                                break;
                            case 'service_id':
                                $query->whereIn($column, $value);
                                break;
                            default:
                                if (is_numeric($value)) {
                                    $query->where($column, $value);
                                } elseif (isset($dateRanges[$column]) && is_array($dateRanges[$column])) {
                                    $query->whereBetween(
                                        $column,
                                        [
                                            Carbon::parse($dateRanges[$column][0])->startOfDay(),
                                            Carbon::parse($dateRanges[$column][1])->endOfDay(),
                                        ]
                                    );
                                }
                                break;
                        }
                    });
                }
            })
            ->latest('id')
            ->latest('datetime');
    }
}

