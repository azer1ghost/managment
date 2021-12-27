<?php

namespace App\View\Components\Widgets;

use App\Models\Inquiry;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Carbon\Carbon;

class InquiryPersonalMonthlyWidget extends Component
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
            ->where('user_id', auth()->id())
            ->where('datetime', '>=', now()->startOfMonth())
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->datetime)->format('m-d-Y');
            })];
    }

    public function render()
    {
        if (auth()->user()->inquiries()->exists()) {
            return view('components.widgets.inquiryPersonalDaily-widget');

        }
    }
}
