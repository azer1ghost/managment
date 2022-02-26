<?php

namespace App\View\Components\Widgets;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use App\Traits\GetClassInfo;
use App\Models\Work;

class WorkMonthlyWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public array $results = [];

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        if (Cache::has("{$this->widget->getAttribute('key')}_widget")) {
            $this->results = Cache::get("{$this->widget->getAttribute('key')}_widget");
        } else {
            $data = [];

            $works = Work::query()
                ->select(['id', 'datetime', 'user_id'])
                ->whereDate('datetime', '>=', now()->startOfMonth())
                ->orderBy('datetime')
                ->worksDone()
                ->get()
                ->groupBy(function($work) {
                    return $work->datetime->format('d');
                });

            $works->each(function ($works) use (&$data){
                $data[] = $works->count();
            });

            $this->results['keys'] = $works->keys()->toArray();
            $this->results['data'] = $data;

            Cache::put("{$this->widget->getAttribute('key')}_widget", $this->results, 7200);
        }
    }

    public function render()
    {
        return view('components.widgets.workMonthly-widget');
    }
}
