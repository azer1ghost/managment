<?php

namespace App\Repositories;

use App\Interfaces\ClientRepositoryInterface;
use App\Models\Client;
use Carbon\Carbon;

class ClientRepository implements ClientRepositoryInterface
{

    public function allFilteredClients(array $filters = [])
    {

        [$from, $to] = explode(' - ', $filters['created_at']);
        return Client::with('coordinators')
            ->whereNull('client_id')
            ->when($filters['check-created_at'], fn($query) => $query->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]))
            ->when($filters['free_clients'], fn($query) => $query->doesnthave('salesUsers'))
            ->when($filters['free_company'], fn($query) => $query->doesnthave('companies'))
            ->when($filters['free_coordinator'], fn($query) => $query->doesnthave('coordinators'))
            ->when(is_numeric($filters['type']), fn($query) => $query->where('type', (int)$filters['type']))
            ->when(is_numeric($filters['active']), fn($query) => $query->where('active', (int)$filters['active']))
            ->when(is_numeric($filters['satisfaction']), fn($query) => $query->where('satisfaction', (int)$filters['satisfaction']))
            ->when($filters['coordinator'], fn($query) => $query->whereHas('coordinators', fn($q) => $q->where('id', $filters['coordinator'])))
            ->when($filters['company'], fn($query) => $query->whereHas('companies', fn($q) => $q->where('id', $filters['company'])))
            ->when($filters['search'], fn($query) => $query->where('fullname', 'like', "%{$filters['search']}%")
                ->orWhere('voen', 'like', "%{$filters['search']}%")
                ->orWhere('email1', 'like', "%{$filters['search']}%")
                ->orWhere('phone1', 'like', "%{$filters['search']}%")
                ->orWhere('phone2', 'like', "%{$filters['search']}%"))
            ->when($filters['users'], fn($query) => $query->where('user_id', $filters['users']));
    }
}