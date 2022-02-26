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

    public array $results = [
        'data' => [0, 0, 0, 0, 0, 0]
    ];
    public ?Model $widget;
    public ?string $model = null;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $cache_key = "{$this->widget->getAttribute('key')}_widget";

        if (\Cache::has($cache_key)) {
            $this->results = \Cache::get($cache_key);
        } else {
            $inquiries = Inquiry::isReal()->isCallCenter()->monthly()->select('id')->withCount([
                'parameters as status_active_count' => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::ACTIVE),
                'parameters as status_done_count'   => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::DONE),
                'parameters as status_rejected_count'   => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::REJECTED),
                'parameters as status_incompatible_count'   => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::INCOMPATIBLE),
                'parameters as status_unreachable_count'   => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::UNREACHABLE),
                'parameters as status_redirected_count'   => fn ($q) => $q->where('inquiry_parameter.value', Inquiry::REDIRECTED),
            ])->get()->toArray();

            foreach ($inquiries as $item){
                $this->results['data'][0] += $item['status_active_count'];
                $this->results['data'][1] += $item['status_done_count'];
                $this->results['data'][2] += $item['status_rejected_count'];
                $this->results['data'][3] += $item['status_incompatible_count'];
                $this->results['data'][4] += $item['status_unreachable_count'];
                $this->results['data'][5] += $item['status_redirected_count'];
            }

            foreach (Option::whereRelation('parameters', 'id', Inquiry::STATUS_PARAMETER)->get(['id', 'text']) as $key){
                $this->results['labels'][] = $key->text;
                $this->results['colors'][] = rand_color();
            }

            \Cache::put($cache_key, $this->results, 7200);
        }
    }

    public function render()
    {
        return view('components.widgets.inquiryStatus-widget');
    }
}
