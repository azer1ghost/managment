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

    public array $results = [0, 0, 0, 0, 0, 0], $keys = [], $colors = [];
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

        foreach ($inquiries as $item){
            $this->results[0] += $item['status_active_count'];
            $this->results[1] += $item['status_done_count'];
            $this->results[2] += $item['status_rejected_count'];
            $this->results[3] += $item['status_incompatible_count'];
            $this->results[4] += $item['status_unreachable_count'];
            $this->results[5] += $item['status_redirected_count'];
        }

        foreach (Option::whereRelation('parameters', 'id', Inquiry::STATUS_PARAMETER)->get(['id', 'text']) as $key){
            $this->keys[] = $key->text;
        }
    }

    public function render()
    {
        return view('components.widgets.inquiryStatus-widget');
    }
}
