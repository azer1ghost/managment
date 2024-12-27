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
        // Boş bir sorgu başlatıyoruz.
        $query = Work::query();

        // Filtreleri uygula
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $query->where($key, $value);
            }
        }

        // Tarih aralıklarını uygula
        foreach ($dateFilters as $key => $dateRange) {
            if (!empty($dateRange) && is_array($dateRange) && count($dateRange) === 2) {
                $query->whereBetween($key, [
                    Carbon::parse($dateRange[0])->startOfDay(),
                    Carbon::parse($dateRange[1])->endOfDay(),
                ]);
            }
        }

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
        $query = $this->allFilteredWorks($filters, $dateFilters);
        return $query->count();
    }
}
