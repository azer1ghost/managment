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
        $query = Work::query();

        // Genel filtreleri uygula
        $this->applyFilters($query, $filters);

        // Tarih filtrelerini uygula
        $this->applyDateFilters($query, $dateFilters);

        return $query;
    }

    /**
     * Belirli sütunları döndürür.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWorksWithSpecificColumns(array $columns): \Illuminate\Database\Eloquent\Collection
    {
        return Work::select($columns)->get();
    }

    /**
     * Filtrelenmiş işlerin toplam sayısını döndürür.
     *
     * @param array $filters
     * @param array $dateFilters
     * @return int
     */
    public function countFilteredWorks(array $filters = [], array $dateFilters = []): int
    {
        return $this->allFilteredWorks($filters, $dateFilters)->count();
    }

    /**
     * Genel filtreleri sorguya uygular.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     */
    protected function applyFilters(&$query, array $filters): void
    {
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                // Özelleştirilmiş filtreleme işlemleri
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

    /**
     * Tarih filtrelerini sorguya uygular.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $dateFilters
     */
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
