<?php

namespace App\Interfaces;

interface WorkRepositoryInterface
{
    /**
     * Get all works with applied filters and date ranges.
     *
     * @param array $filters Key-value pairs for filtering works.
     * @param array $dateFilters Date ranges, e.g. ['start_date' => 'Y-m-d', 'end_date' => 'Y-m-d'].
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allFilteredWorks(array $filters = [], array $dateFilters = []): \Illuminate\Database\Eloquent\Builder;

    /**
     * Additional method to get specific fields or customized data.
     *
     * @param array $columns Columns to select from the works table.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWorksWithSpecificColumns(array $columns): \Illuminate\Database\Eloquent\Collection;

    /**
     * Count the filtered works for optimization or pagination.
     *
     * @param array $filters Key-value pairs for filtering works.
     * @param array $dateFilters Date ranges.
     * @return int
     */
    public function countFilteredWorks(array $filters = [], array $dateFilters = []): int;
}
