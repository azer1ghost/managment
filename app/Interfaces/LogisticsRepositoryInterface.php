<?php

namespace App\Interfaces;

interface LogisticsRepositoryInterface
{
    public function allFilteredLogistics(array $filters = [], array $dateFilters = []);
}