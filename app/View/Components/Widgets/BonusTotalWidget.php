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

        $referral = optional(auth()->user()->getRelationValue('referral'));
        $effective = $referral->total_users * $referral->efficiency / 100 ;
        $ineffective = $referral->total_users - $effective;

        $this->results = \Cache::remember("{$this->widget->getAttribute('key')}_widget", 7200, function () use ($effective, $ineffective){
            return [
                (object) [
                    'label' => __('translates.bonus.effective'),
                    'total' => $effective
                ],
                (object) [
                    'label' => __('translates.bonus.ineffective'),
                    'total' => $ineffective
                ]
            ];
        });
    }

    public function render()
    {
        if(auth()->user()->referral()->exists()){
            return view('components.widgets.bonusTotal-widget');
        }
    }
}
