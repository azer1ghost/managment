<?php

namespace App\View\Components\Widgets;

use App\Models\User;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class InquiryUserWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public Collection $result;


    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $this->result = User::withCount('inquiries')
            ->whereHas('inquiries', function ($q) {
                $q->isReal();
            })
            ->orderByDesc('inquiries_count')
            ->get()
            ->map(function ($result) {
                return [
                    'name' => $result->getAttribute('fullname'),
                    'steps' => $result->inquiries_count,
                    'pictureSettings' => [
                        'src' => image($result->getAttribute('avatar'))
                    ]

                ];
            });
    }

    public function render()
    {
        return view('components.widgets.inquiryUser-widget');
    }
}
