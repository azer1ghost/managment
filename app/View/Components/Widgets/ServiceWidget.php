<?php

namespace App\View\Components\Widgets;

use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class ServiceWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();
    }

    public function render()
    {
        return view('components.widgets.service-widget');
    }
}
