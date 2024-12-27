<?php

namespace App\Repositories;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Work;
use Carbon\Carbon;

class WorkRepository implements WorkRepositoryInterface
{
    public function allFilteredWorks(array $filters = [], array $dateFilters = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = Work::query();

        $this->applyFilters($query, $filters);

        $this->applyDateFilters($query, $dateFilters);

        return $query;
    }

    public function getWorksWithSpecificColumns(array $columns): \Illuminate\Database\Eloquent\Collection
    {
        return Work::select($columns)->get();
    }

    public function countFilteredWorks(array $filters = [], array $dateFilters = []): int
    {
        return $this->allFilteredWorks($filters, $dateFilters)->count();
    }

    protected function applyFilters(&$query, array $filters): void
    {
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                if ($key === 'limit') {
                    continue;
                }

                if (is_array($value)) {
                    $query->whereIn($key, $value);
                } elseif (is_string($value)) {
                    $query->where($key, 'LIKE', "%$value%");
                } else {
                    $query->where($key, $value);
                }
            }
        }
    }


    protected function applyDateFilters(&$query, array $dateFilters): void
    {
        foreach ($dateFilters as $key => $dateRange) {
            if (!empty($dateRange) && is_array($dateRange) && count($dateRange) === 2) {
                $query->whereBetween($key, [
                    Carbon::parse($dateRange[0])->startOfDay(),
                    Carbon::parse($dateRange[1])->endOfDay(),
                ]);
            }
        }
    }
}
