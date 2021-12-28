<?php

namespace App\View\Components\Widgets;

use App\Models\User;
use App\Traits\GetClassInfo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class InquiryUserWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public array $result = [];


    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $this->result = User::withCount('inquiries')
            ->whereHas('inquiries', function ($q){
                $q->isReal();
            })
            ->orderBy('inquiries_count', 'desc')
            ->get()
            ->map(function ($result){
                return [
                    'name' => $result->getAttribute('fullname'),
                    'steps' => $result->inquiries_count,
                    'pictureSettings'=> $result->getAttribute('avatar')

                ];
            })->toArray();

        dd($this->result );
//        {
//            name: "Monica",
//                steps: 45688,
//                pictureSettings: {
//            src: "https://www.amcharts.com/wp-content/uploads/2019/04/monica.jpg"
//                }
//            },
    }

    public function render()
    {
        return view('components.widgets.inquiryUser-widget');
    }
}
