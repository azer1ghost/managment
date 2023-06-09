<?php

namespace App\Repositories;

use App\Interfaces\CreditorRepositoryInterface;
use App\Models\Creditor;

class CreditorRepository implements CreditorRepositoryInterface
{

    public function allFilteredCreditors(array $filters = [])
    {

        return Creditor::when($filters['search'], fn($query) => $query
            ->whereHas('supplier',fn($q) => $q->where('name', 'like', "%{$filters['search']}%"))
            ->orWhere('note', (int)$filters['search']))

            ->when(is_numeric($filters['status']), fn($query) => $query->where('status', (int)$filters['status']));
//            ->when(is_numeric($filters['active']), fn($query) => $query->where('active', (int)$filters['active']))
//            ->when(is_numeric($filters['satisfaction']), fn($query) => $query->where('satisfaction', (int)$filters['satisfaction']))
//            ->when($filters['coordinator'], fn($query) => $query->whereHas('coordinators', fn($q) => $q->where('id', $filters['coordinator'])))
//            ->when($filters['company'], fn($query) => $query->whereHas('companies', fn($q) => $q->where('id', $filters['company'])))
//            ->when($filters['users'], fn($query) => $query->where('user_id', $filters['users']))
//            ->when($filters['search'], fn($query) => $query->where('fullname', 'like', "%{$filters['search']}%")
//                ->orWhere('voen', 'like', "%{$filters['search']}%")
//                ->orWhere('email1', 'like', "%{$filters['search']}%")
//                ->orWhere('phone1', 'like', "%{$filters['search']}%")
//                ->orWhere('phone2', 'like', "%{$filters['search']}%"));

    }
}