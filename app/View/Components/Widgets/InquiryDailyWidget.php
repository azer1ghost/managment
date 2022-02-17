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

        $this->results = ['data' => Inquiry::isReal()->select('id', 'datetime')
            ->where('datetime', '>=', Carbon::now()->subWeek())
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->datetime)->format('m-d-Y');
            })];

//        dd($this->results);

    }


    public function render()
    {
        return view('components.widgets.inquiryDaily-widget');
    }
}
