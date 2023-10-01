<?php

namespace App\Repositories;

use App\Interfaces\CreditorRepositoryInterface;
use App\Models\Creditor;

class CreditorRepository implements CreditorRepositoryInterface
{
    public function allFilteredCreditors(array $filters = [])
    {
        return Creditor::when($filters['search'], fn($query) => $query
            ->whereHas('supplier', fn($q) => $q->where('name', 'like', "%{$filters['search']}%"))
            ->orWhere('note', $filters['search'])
            ->orWhere('creditor', $filters['search']))
            ->when(is_numeric($filters['status']), fn($query) => $query->where('status', (int)$filters['status']))
            ->when(is_numeric($filters['company']), fn($query) => $query->where('company_id', (int)$filters['company']))
            ->when(is_numeric($filters['supplier']), fn($query) => $query->where('supplier_id', (int)$filters['supplier']));
    }
}