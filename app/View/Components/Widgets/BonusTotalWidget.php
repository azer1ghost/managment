<?php

namespace App\View\Components\Widgets;

use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class BonusTotalWidget extends Component
{
    use GetClassInfo;

    public array $results = [];
    public ?Model $widget;
    public ?string $model = null;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $referral = optional(auth()->user()->referral);
        $effective = $referral->total_users * $referral->efficiency / 100 ;
        $ineffective = $referral->total_users - $effective;

        $this->results = [
            (object) [
                'users' => 'Effective',
                'total' => $effective
            ],
            (object) [
                'users' => 'Ineffective',
                'total' => $ineffective
            ]
        ];
    }

    public function render()
    {
        return view('components.widgets.bonusTotal-widget');
    }
}
