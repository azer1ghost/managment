<?php

namespace App\View\Components\Widgets;

use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class TaskDoneWidget extends Component
{
    use GetClassInfo;

    public array $results = [];
    public ?Model $widget;
    public ?string $model = null;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $users = User::withCount(['tasks as tasks_done_count' => fn($q) => $q->where('status', 'done')])
            ->orderBy('tasks_done_count', 'desc')
            ->limit(6)
            ->get(['name', 'surname', 'avatar']);

        $departments = Department::withCount(['tasks as tasks_done_count' => fn($q) => $q->where('status', 'done')])
            ->orderBy('tasks_done_count', 'desc')
            ->limit(6)
            ->get(['name']);

        foreach ($users as $user) {
            $this->results['users'][] = [
                'name' => "{$user->name} {$user->surname}",
                'steps' => $user->tasks_done_count,
                'href' => image($user->avatar)
            ];
        }

        foreach ($departments as $dep) {
            $this->results['departments'][] = [
                'name' => "{$dep->name}",
                'steps' => $dep->tasks_done_count,
                'href' => image('no_image')
            ];
        }
    }

    public function render()
    {
        return view('components.widgets.taskDone-widget');
    }
}
