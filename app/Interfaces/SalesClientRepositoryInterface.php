<?php

namespace App\Interfaces;

interface SalesClientRepositoryInterface
{
    public function allFilteredClients(array $filters = []);
}