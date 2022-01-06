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

    public function checkUserInquiries($q)
    {
        return $q->where('datetime', '>=', now()->startOfMonth())->isReal();
    }

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();
        $this->result = User::where('department_id', auth()->user()->getAttribute('department_id'))->whereHas('inquiries', fn($q) => $this->checkUserInquiries($q))
            ->withCount([
                'inquiries' => fn($q) => $this->checkUserInquiries($q)
            ])
            ->orderBy('inquiries_count')
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
