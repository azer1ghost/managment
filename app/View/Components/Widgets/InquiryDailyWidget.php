<?php

namespace App\View\Components\Widgets;

use App\Models\Inquiry;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Carbon\Carbon;

class InquiryDailyWidget extends Component
{
    use GetClassInfo;

    public array $results = [];
    public ?Model $widget;
    public ?string $model = null;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $this->results = \Cache::remember("{$this->widget->getAttribute('key')}_widget", 1, function () {
            $data = Inquiry::isReal()->select('id', 'datetime')
                ->where('datetime', '>=', now()->subWeek())
                ->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->datetime)->format('d-m');
                })
                ->toArray();

            return array_map(function ($item) {
                return count($item);
            }, $data);
        });
    }

    public function render()
    {
        return view('components.widgets.inquiryDaily-widget');
    }
}
