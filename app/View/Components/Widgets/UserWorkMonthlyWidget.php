<?php

namespace App\View\Components\Widgets;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use App\Traits\GetClassInfo;
use App\Models\Work;

class UserWorkMonthlyWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public $works;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $this->works = DB::table('users')
            ->select('users.name', DB::raw('SUM(work_parameter.value) AS total_value'))
            ->join('works', 'works.user_id', '=', 'users.id')
            ->join('work_parameter', 'works.id', '=', 'work_parameter.work_id')
            ->where('work_parameter.parameter_id', '=', Work::GB)
            ->where('disabled_at', '=', null)
            ->whereDate('datetime', '>=', now()->startOfMonth())
            ->groupBy('users.name')
            ->orderByDesc('total_value')
            ->get();
    }

    public function render()
    {
        return view('components.widgets.userWorkMonthly-widget');
    }
}

