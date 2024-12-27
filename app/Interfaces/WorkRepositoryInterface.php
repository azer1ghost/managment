<?php

namespace App\Repositories;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Work;
use Carbon\Carbon;

class WorkRepository implements WorkRepositoryInterface
{
    public function allFilteredWorks(array $filters = [], array $dateFilters = []): \Illuminate\Database\Eloquent\Builder
    {
        // allFilteredWorks metodu daha önce yazıldığı gibi
        // Burada mevcut kodunuz yer alacak
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
