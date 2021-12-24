<?php

namespace App\Interfaces;

interface ClientRepositoryInterface
{
    public function allFilteredClients(array $filters = []);
}