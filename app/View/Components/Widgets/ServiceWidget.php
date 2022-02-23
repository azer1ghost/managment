<?php

namespace App\View\Components\Widgets;

use App\Models\Department;
use App\Models\Service;
use App\Models\Work;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ServiceWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public $services;
    public  array $colors;
    public ?Collection $works;
    public int $total;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $this->colors = [
            'bg-primary',
            'bg-primary',
            'bg-success',
            'bg-success',
            'bg-danger',
            'bg-danger',
            'bg-warning',
            'bg-warning',
            'bg-info',
            'bg-info',
            'bg-secondary',
        ];

        $serviceQuery = Service::withCount('works')
            ->whereHas('works', function ($q){
                $q->where('status', '!=', Work::REJECTED);
            })
            ->orderBy('works_count', 'desc')
            ->get();



        $this->works = Department::withCount('works')
            ->whereHas('works', function ($q){
                $q->where('status', '!=', Work::REJECTED);
            })
            ->get()
            ->map(function ($work){
                return [
                    'y' => count($work->getRelationValue('works')),
                    'label' => $work->getAttribute('name'),
                ];
            });

        $this->total = $this->works->reduce(fn($acc, $work) => $acc + $work['y'], 0);
        $this->services  = $serviceQuery;
    }

    public function render()
    {
        return view('components.widgets.service-widget');
    }
}
