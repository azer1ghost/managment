<?php

namespace App\Interfaces;

interface WorkRepositoryInterface
{
    public function allFilteredWorks(array $filters = [], array $dateFilters = []);
}
