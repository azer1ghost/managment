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

        if (Cache::has("{$this->widget->getAttribute('key')}_widget")) {
            $this->results = Cache::get("{$this->widget->getAttribute('key')}_widget");
        } else {
            $data = [];

            $works = auth()->user()
                ->works()
                ->select(['id', 'datetime', 'user_id'])
                ->whereDate('datetime', '>=', now()->startOfMonth())
                ->orderBy('datetime')
                ->worksDone()
                ->get()
                ->groupBy(function($work) {
                    return $work->datetime->format('d');
                });

            $works->each(function ($works) use (&$data){
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
