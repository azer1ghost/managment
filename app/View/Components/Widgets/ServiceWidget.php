<?php

namespace App\View\Components\Widgets;

use App\Models\Service;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ServiceWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public ?Collection $services;


    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $this->services = Service::withCount('works')->has('works')->orderBy('works_count', 'desc')->get()->map(function ($service){
            return [
                'service' => $service->getAttribute('name'),
                'total' => count($service->getRelationValue('works')),
            ];
        });
    }

    public function render()
    {
        return view('components.widgets.service-widget');
    }
}
