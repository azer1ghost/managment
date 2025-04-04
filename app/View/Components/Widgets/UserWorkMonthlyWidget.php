<?php

namespace App\View\Components\Widgets;

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

        $userId = auth()->id();
        $specialUserIds = [17, 124, 15, 123];

        // Əsas sorğu
        $query = DB::table('users')
            ->select('users.name', 'users.surname', DB::raw('SUM(work_parameter.value) AS total_value'))
            ->join('works', 'works.user_id', '=', 'users.id')
            ->join('work_parameter', 'works.id', '=', 'work_parameter.work_id')
            ->where('work_parameter.parameter_id', '=', Work::GB)
            ->where('disabled_at', '=', null)
            ->where('works.deleted_at', '=', null)
            ->whereDate('entry_date', '>=', now()->startOfMonth())
            ->groupBy('users.name', 'users.surname', 'users.department_id')
            ->orderByDesc('total_value');

        // Əgər istifadəçi xüsusi ID-lərdəndirsə, bütün məlumatları gətir
        if (!in_array($userId, $specialUserIds)) {
            // Əks halda, istifadəçinin departamentini al
            $departmentId = DB::table('users')->where('id', $userId)->value('department_id');

            // Sorğunu departamentə görə filtr et
            $query->where('users.department_id', '=', $departmentId);
        }

        // Sorğunu icra edərək nəticəni `$this->works`-ə təyin et
        $this->works = $query->get();
    }


    public function render()
    {
        return view('components.widgets.userWorkMonthly-widget');
    }
}

