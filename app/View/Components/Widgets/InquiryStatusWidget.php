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

        $statuses = [0, 0, 0, 0, 0, 0, 0];

        foreach ($inquiries as $item){
            $statuses[0] += $item['status_active_count'] ?? 0;
            $statuses[1] += $item['status_done_count'] ?? 0;
            $statuses[2] += $item['status_rejected_count'] ?? 0;
            $statuses[3] += $item['status_incompatible_count'] ?? 0;
            $statuses[4] += $item['status_unreachable_count'] ?? 0;
            $statuses[5] += $item['status_redirected_count'] ?? 0;
        }

        // inquiries where status not selected
        $statuses[6] = Inquiry::isReal()->monthly()->count() - array_sum($statuses);

        $this->results = $statuses;

        foreach (Option::whereRelation('parameters', 'id', Inquiry::STATUS_PARAMETER)->get(['id', 'text']) as $key){
            $this->keys[] = $key->text;
        }

        $this->keys[] =  __('translates.filters.select');
    }

    public function render()
    {
        return view('components.widgets.inquiryStatus-widget');
    }
}
