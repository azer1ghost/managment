<?php

namespace App\View\Components\Widgets;

use App\Models\Inquiry;
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
    public Collection $users;
    public array $colors;

    public function checkUserInquiries($q)
    {
        return $q->where('datetime', '>=', now()->startOfMonth())->isReal();
    }

    public function __construct($widget)
    {
       $this->colors = [
            'bg-primary',
            'bg-primary',
            'bg-success',
            'bg-success',
            'bg-danger',
            'bg-danger',
            'bg-warning',
            'bg-warning',
            'bg-info',
            'bg-info',
            'bg-secondary',
        ];

        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $this->users = User::when(!Inquiry::userCanViewAll(), fn($q) => $q
            ->where('department_id', auth()->user()->getAttribute('department_id')))
            ->whereHas('inquiries', fn($q) => $this->checkUserInquiries($q))
            ->withCount([
                'inquiries' => fn($q) => $this->checkUserInquiries($q)
            ])
            ->orderByDesc('inquiries_count')
            ->get();
    }

    public function render()
    {
        return view('components.widgets.inquiryUser-widget');
    }
}
