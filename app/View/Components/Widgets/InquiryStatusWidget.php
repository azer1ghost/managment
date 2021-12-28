<?php

namespace App\View\Components\Widgets;

use App\Models\Inquiry;
use App\Models\Option;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class InquiryStatusWidget extends Component
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

        $inquiries = Inquiry::isReal()->monthly()->select('id')->withCount([
            'parameters as status_active_count' => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::ACTIVE),
            'parameters as status_done_count'   => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::DONE),
            'parameters as status_rejected_count'   => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::REJECTED),
            'parameters as status_incompatible_count'   => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::INCOMPATIBLE),
            'parameters as status_unreachable_count'   => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::UNREACHABLE),
            'parameters as status_redirected_count'   => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::REDIRECTED),
        ])->get()->toArray();

        $statuses = [0, 0, 0, 0, 0, 0];

        foreach ($inquiries as $item){
            $statuses[0] += $item['status_active_count'];
            $statuses[1] += $item['status_done_count'];
            $statuses[2] += $item['status_rejected_count'];
            $statuses[3] += $item['status_incompatible_count'];
            $statuses[4] += $item['status_unreachable_count'];
            $statuses[5] += $item['status_redirected_count'];
        }

        $this->total = Inquiry::isReal()->monthly()->count();

        $statuses[6] = $this->total - array_sum($statuses);

        $this->results = $statuses;

        foreach (Option::whereRelation('parameters', 'id', 5)->get(['id', 'text']) as $key){
            $this->keys[] = $key->text;
        }

        $this->keys[] =  __('translates.filters.select');
    }

    public function render()
    {
        return view('components.widgets.inquiryStatus-widget');
    }
}
