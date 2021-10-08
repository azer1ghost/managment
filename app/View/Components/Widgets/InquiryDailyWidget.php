<?php

namespace App\View\Components\Widgets;

use App\Models\Inquiry;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class InquiryDailyWidget extends Component
{
    use GetClassInfo;

    public array $results = [], $keys = [], $colors = [];
    public int $total = 0;
    public ?Model $widget;
    public ?string $model = null;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $inquiries = Inquiry::isReal()->select('id')->withCount([
            'parameters as status_active_count' => fn ($q) => $q->where('inquiry_parameter.value', 21),
            'parameters as status_done_count'   => fn ($q) => $q->where('inquiry_parameter.value', 22),
            'parameters as status_rejected_count'   => fn ($q) => $q->where('inquiry_parameter.value', 23),
            'parameters as status_incompatible_count'   => fn ($q) => $q->where('inquiry_parameter.value', 24),
            'parameters as status_unreachable_count'   => fn ($q) => $q->where('inquiry_parameter.value', 25),
        ])->get()->toArray();

        $statuses = [0, 0, 0, 0, 0, 0];

        foreach ($inquiries as $item){
            $statuses[0] += $item['status_active_count'];
            $statuses[1] += $item['status_done_count'];
            $statuses[2] += $item['status_rejected_count'];
            $statuses[3] += $item['status_incompatible_count'];
            $statuses[4] += $item['status_unreachable_count'];
        }

        $this->total = Inquiry::isReal()->count();

        $statuses[5] = $this->total - array_sum($statuses);

        $this->results = $statuses;
        $this->keys = [
            'Active',
            'Done',
            'Rejected',
            'Incompatible',
            'Unreachable',
            'Not selected'
        ];
    }

    public function render()
    {
        return view('components.widgets.inquiry-daily-widget');
    }
}
