<?php

namespace App\View\Components\Widgets;

use App\Models\Work;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

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
    }

    public function render()
    {
        return view('components.widgets.workMonthly-widget');
    }
}
