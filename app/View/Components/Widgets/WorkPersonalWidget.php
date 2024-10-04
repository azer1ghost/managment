<?php /** @noinspection NullPointerExceptionInspection */

namespace App\View\Components\Widgets;

use App\Models\Work;
use App\Traits\GetClassInfo;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class WorkPersonalWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public array $results = [];

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();
        $auth = auth()->id();
        $authDepartmentId = auth()->user()->department_id;

        if (Cache::has("{$this->widget->getAttribute('key')}_{$auth}_widget")) {
            $this->results = Cache::get("{$this->widget->getAttribute('key')}_widget");
        } else {
            $data = [];

            $allowedUserIds = [17, 124, 15, 123];


            if (in_array($auth, $allowedUserIds)) {
                $works = Work::select(['id', 'datetime', 'user_id'])
                    ->whereDate('datetime', '>=', now()->startOfMonth())
                    ->orderBy('datetime')
                    ->worksDone()
                    ->get()
                    ->groupBy(function ($work) {
                        return $work->datetime->format('d');
                    });
            } else {
                $works = Work::select(['id', 'datetime', 'user_id'])
                    ->whereHas('user', function ($query) use ($authDepartmentId) {
                        $query->where('department_id', $authDepartmentId);
                    })
                    ->whereDate('datetime', '>=', now()->startOfMonth())
                    ->orderBy('datetime')
                    ->worksDone()
                    ->get()
                    ->groupBy(function ($work) {
                        return $work->datetime->format('d');
                    });
            }

            $works->each(function ($works) use (&$data) {
                $data[] = $works->count();
            });

            $this->results['keys'] = $works->keys()->toArray();
            $this->results['data'] = $data;

            Cache::put("{$this->widget->getAttribute('key')}_widget", $this->results, 7200);
        }
    }



    public function render()
    {
        if (auth()->user()->works()->exists()){
            return view('components.widgets.workPersonal-widget');
        }
    }
}
