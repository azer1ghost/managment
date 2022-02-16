<?php

namespace App\View\Components\Widgets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use App\Traits\GetClassInfo;
use App\Models\Work;

class WorkMonthlyWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public $works;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $this->works = Work::select(['id', 'datetime', 'verified_at'])
            ->whereDate('datetime', '>=', now()->startOfMonth())
            ->orderBy('datetime')
            ->worksDone()
            ->get()
            ->groupBy(function($work) {
                return $work->datetime->format('d');
            })->map(function ($works, $day){
                return [
                    'day' => $day,
                    'total' => $works->count(),
                    'verified' => $works->where('verified_at', '!=', NULL)->count(),
                ];
            })->values();
//        dd($this->widget);
    }

    public function render()
    {
        return view('components.widgets.workMonthly-widget');
    }
}
