<?php

namespace App\Repositories;

use App\Interfaces\LogisticsRepositoryInterface;
use App\Models\Logistics;
use App\Models\Work;
use Carbon\Carbon;

class LogisticsRepository implements LogisticsRepositoryInterface {

    public function allFilteredLogistics(array $filters = [], $dateFilters = [])
    {
        $user = auth()->user();
        $dateRanges = [
            'datetime' => explode(' - ', $filters['datetime']),
            'created_at' => explode(' - ', $filters['created_at']),
//            'paid_at' => explode(' - ', $filters['paid_at']),
        ];

        return Logistics::query()
            ->with([
                'service',
                'user:id,name,surname,department_id,permissions',
                'client:id,name,voen',
            ])
            ->where(function($query) use ($filters, $dateRanges, $dateFilters){
                foreach ($filters as $column => $value) {
                    if($column == 'limit') continue;
                    $query->when($value, function ($query, $value) use ($column, $dateRanges, $dateFilters) {
                        if ($column == 'paid_at'){
                            switch ($value){
                                case 1:
                                    $query->whereNull($column);
                                    break;
                                case 2:
                                    $query->whereNotNull($column);
                                    break;
                            }
                        }
                       else{
                            if($column == 'reg_number'){
                                $query->where($column, 'LIKE', "%$value%");
                            }
                            elseif($column == 'service_id'){
                                $query->whereIn($column, $value);
                            }
                            else if (is_numeric($value)){
                                $query->where($column, $value);
                            }
                            else if(is_string($value) && $dateFilters[$column]){
                                $query->whereBetween($column,
                                    [
                                        Carbon::parse($dateRanges[$column][0])->startOfDay(),
                                        Carbon::parse($dateRanges[$column][1])->endOfDay()
                                    ]
                                );
                            }
                        }
                    });
                }
            })
            ->latest('id')
            ->latest('datetime');
//            ->orderBy('status')
//        ->orderByDesc('id');
    }
}