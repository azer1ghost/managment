<?php /** @noinspection NullPointerExceptionInspection */

namespace App\View\Components\Widgets;

use App\Models\Work;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class WorkPersonalWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public $works;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $this->works = Work::select(['id', 'datetime', 'user_id', 'verified_at'])
            ->where('user_id', auth()->id())
            ->whereDate('datetime', '>=', now()->startOfMonth())
            ->orderBy('datetime')
            ->worksDone()
            ->get()
            ->groupBy(function($work) {
                return $work->datetime->format('d');
            })->map(function ($works, $day){
                return [
                    'day' => $day,
                    'total' => $works->count(),
                    'verified' => $works->where('verified_at', '!=', NULL)->count(),
                ];
            })->values();
    }

    public function render()
    {
        if (auth()->user()->works()->exists()){
            return view('components.widgets.workPersonal-widget');
        }
    }
}
