<?php

namespace App\Repositories;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Work;
use Carbon\Carbon;

class WorkRepository implements WorkRepositoryInterface
{
    /**
     * Filtrelenmiş işlerin sorgusunu döndürür.
     *
     * @param array $filters
     * @param array $dateFilters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allFilteredWorks(array $filters = [], array $dateFilters = []): \Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user();

        // Tarih aralıklarını oluştur
        $dateRanges = $this->parseDateRanges($filters, [
            'datetime', 'created_at', 'injected_at', 'entry_date', 'invoiced_date', 'vat_date',
        ]);

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
            ->when($status, fn($query) => $query->where('status', $status))
            ->when(!empty($statuses), fn($query) => $query->whereNotIn('status', $statuses))
            ->when(Work::userCannotViewAll(), fn($query) => $this->applyUserPermissions($query, $user))
            ->where(function ($query) use ($filters, $dateRanges) {
                foreach ($filters as $column => $value) {
                    if ($column === 'limit') {
                        continue;
                    }
                    $this->applyColumnFilter($query, $column, $value, $dateRanges);
                }
            })
            ->latest('id')
            ->latest('datetime');
    }

    /**
     * Kullanıcı izinlerini uygula.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyUserPermissions($query, $user)
    {
        if ($user->hasPermission('viewAllDepartment-work')) {
            $query->where('department_id', $user->department_id);
        } else {
            $query->where(function ($q) use ($user) {
                $q->whereNull('user_id')
                    ->where('department_id', $user->department_id);
            })->orWhere('user_id', $user->id);
        }
        return $query;
    }

    /**
     * Tarih aralıklarını oluşturur.
     *
     * @param array $filters
     * @param array $keys
     * @return array
     */
    protected function parseDateRanges(array $filters, array $keys): array
    {
        $dateRanges = [];
        foreach ($keys as $key) {
            $dateRanges[$key] = !empty($filters[$key]) ? explode(' - ', $filters[$key]) : null;
        }
        return $dateRanges;
    }

    /**
     * Belirli bir sütuna göre filtre uygula.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $column
     * @param mixed $value
     * @param array $dateRanges
     */
    protected function applyColumnFilter($query, string $column, $value, array $dateRanges)
    {
        $query->when($value, function ($query, $value) use ($column, $dateRanges) {
            switch ($column) {
                case 'verified_at':
                case 'paid_at':
                    $query->where($column, $value == 1 ? null : '!=', null);
                    break;
                case 'asan_imza_company_id':
                    $query->whereHas('asanImza', function ($asanImzaQuery) use ($value) {
                        $asanImzaQuery->whereHas('company', fn($companyQuery) => $companyQuery->where('id', $value));
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
}
