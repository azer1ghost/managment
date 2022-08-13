<?php

namespace App\Repositories;

use App\Interfaces\ClientRepositoryInterface;
use App\Models\Client;
use Carbon\Carbon;

class ClientRepository implements ClientRepositoryInterface {

    public function allFilteredClients(array $filters = [])
    {

        [$from, $to] = explode(' - ', $filters['created_at']);
        return Client::with('salesUsers')
            ->whereNull('client_id')
            ->when(Client::userCannotViewAll(), function ($query){
                $query->where(function ($query){
                    $query
                        ->doesnthave('salesUsers')
                        ->orWhereHas('salesUsers', fn($q) => $q->where('id', auth()->id()));

                });
            })
            ->when($filters['check-created_at'], fn($query) => $query->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]))
            ->when($filters['free_clients'], fn ($query) => $query->doesnthave('salesUsers'))
            ->when(is_numeric($filters['type']), fn ($query) => $query->where('type', (int) $filters['type']))
            ->when(is_numeric($filters['satisfaction']), fn ($query) => $query->where('satisfaction', (int) $filters['satisfaction']))
            ->when($filters['search'], fn ($query) => $query->where('fullname', 'like', "%{$filters['search']}%")
                                                            ->orWhere('voen', 'like' , "%{$filters['search']}%")
                                                            ->orWhere('phone1', 'like', "%{$filters['search']}%")
                                                            ->orWhere('phone2', 'like', "%{$filters['search']}%"))
            ->when($filters['salesClient'], fn ($query) => $query->whereHas('salesUsers', fn($q) => $q->where('id', $filters['salesClient'])));
    }
}