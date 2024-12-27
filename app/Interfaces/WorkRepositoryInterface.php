<?php

namespace App\Interfaces;

interface WorkRepositoryInterface
{
    public function allFilteredWorks(array $filters = [], array $dateFilters = []): \Illuminate\Database\Eloquent\Builder;

    public function getWorksWithSpecificColumns(array $columns): \Illuminate\Database\Eloquent\Collection;

    public function countFilteredWorks(array $filters = [], array $dateFilters = []): int;
}
