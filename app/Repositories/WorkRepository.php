<?php

namespace App\Repositories;

use App\Interfaces\WorkRepositoryInterface;
use App\Models\Work;
use Carbon\Carbon;

class WorkRepository implements WorkRepositoryInterface {

    public function allFilteredWorks(array $filters = [], $dateFilters = [])
    {
        $user = auth()->user();
        $dateRanges = [
            'datetime' => explode(' - ', $filters['datetime']),
            'created_at' => explode(' - ', $filters['created_at']),
            'injected_at' => explode(' - ', $filters['injected_at']),
            'entry_date' => explode(' - ', $filters['entry_date']),
            'invoiced_date' => explode(' - ', $filters['invoiced_date']),
            'vat_date' => explode(' - ', $filters['vat_date']),
            'paid_at' => explode(' - ', $filters['paid_at']),
        ];
        $status = $filters['status'];
        $statuses = $filters['statuses'] ?? [];

        return Work::query()
            ->select('works.*')
            ->with([
                'creator','department:id,name,short_name',
                'service',
                'user:id,name,surname,department_id,permissions',
                'client:id,fullname,voen',
                'asanImza:id,user_id,company_id'
            ])
            ->when(!empty($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when(!empty($statuses), function ($query) use ($statuses) {
                $query->whereNotIn('status', $statuses);
            })
            ->when(Work::userCannotViewAll(), function ($query) use ($user){
                if($user->hasPermission('viewAllDepartment-work')){
                    $query->where('department_id', $user->getAttribute('department_id'));
                }else{
                    $query
                        ->where(function ($q) use ($user){
                            $q->whereNull('user_id')->where('department_id', $user->getAttribute('department_id'));
                        })
                        ->orWhere('user_id', $user->getAttribute('id'));
                }
            })
            ->where(function($query) use ($filters, $dateRanges, $dateFilters){
                foreach ($filters as $column => $value) {
                    if($column == 'limit') continue;
                    $query->when($value, function ($query, $value) use ($column, $dateRanges, $dateFilters) {
                        if($column == 'verified_at'){
                            switch ($value){
                                case 1:
                                    $query->whereNull($column);
                                    break;
                                case 2:
                                    $query->whereNotNull($column);
                                    break;
                            }
                        }
                        else if ($column == 'paid_at'){
                            switch ($value){
                                case 1:
                                    $query->whereNull($column);
                                    break;
                                case 2:
                                    $query->whereNotNull($column);
                                    break;
                            }
                        }
                        else if($column == 'asan_imza_company_id'){
                            $query->whereHas('asanImza', function ($asanImzaQuery) use ($value) {
                                $asanImzaQuery->whereHas('company', function ($companyQuery) use ($value) {
                                    $companyQuery->whereId($value);
                                });
                            });
                        }else{
                            if($column == 'code'){
                                $query->where($column, 'LIKE', "%$value%");
                            }
                            elseif($column == 'declaration_no'){
                                $query->where($column, 'LIKE', "%$value%");
                            }
                            elseif($column == 'transport_no'){
                                $query->where($column, 'LIKE', "%$value%");
                            }
                            elseif($column == 'service_id'){
                                $query->whereIn($column, $value);
                            }
                            elseif($column == 'destination'){
                                $query->where($column, $value);
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
