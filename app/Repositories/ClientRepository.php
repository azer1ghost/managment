<?php

namespace App\Repositories;

use App\Interfaces\ClientRepositoryInterface;
use App\Models\Client;

class ClientRepository implements ClientRepositoryInterface {

    public function allFilteredClients(array $filters = [])
    {
        return Client::with('salesUsers')
            ->whereNull('client_id')
            ->when(Client::userCannotViewAll(), function ($query){
                $query->where(function ($query){
                    $query
                        ->doesnthave('salesUsers')
                        ->orWhereHas('salesUsers', fn($q) => $q->where('id', auth()->id()));
                });
            })
            ->when($filters['free_clients'], fn ($query) => $query->doesnthave('salesUsers'))
            ->when(is_numeric($filters['type']), fn ($query) => $query->where('type', (int) $filters['type']))
            ->when($filters['search'], fn ($query) => $query->where('fullname', 'like', "%{$filters['search']}%"))
            ->when($filters['salesClient'], fn ($query) => $query->whereHas('salesUsers', fn($q) => $q->where('id', $filters['salesClient'])));
    }
}