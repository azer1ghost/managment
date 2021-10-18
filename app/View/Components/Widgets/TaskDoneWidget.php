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

        $users = User::isActive()->withCount([
                'tasks as tasks_done_count'    => fn($q) => $q->where('status', 'done'),
                'tasks as tasks_ongoing_count' => fn($q) => $q->where('status', '!=', 'done'),
            ])
            ->orderBy('tasks_done_count', 'asc')
            ->orderBy('tasks_ongoing_count', 'asc')
//            ->limit(6)
            ->get(['name', 'surname', 'avatar']);

        $departments = Department::isActive()->withCount([
                'tasks as tasks_done_count'    => fn($q) => $q->where('status', 'done'),
                'tasks as tasks_ongoing_count' => fn($q) => $q->where('status', '!=', 'done'),
            ])
            ->orderBy('tasks_done_count', 'asc')
            ->orderBy('tasks_ongoing_count', 'asc')
//            ->limit(6)
            ->get(['name']);

        foreach ($users as $user) {
            $this->results['done']['users'][] = [
                'name' => "{$user->name} {$user->surname}",
                'steps' => $user->tasks_done_count,
                'href' => image($user->avatar)
            ];
            $this->results['ongoing']['users'][] = [
                'name' => "{$user->name} {$user->surname}",
                'steps' => $user->tasks_ongoing_count,
                'href' => image($user->avatar)
            ];
        }

        foreach ($departments as $dep) {
            $this->results['done']['departments'][] = [
                'name' => "{$dep->name}",
                'steps' => $dep->tasks_done_count,
                'href' => image('no_image')
            ];
            $this->results['ongoing']['departments'][] = [
                'name' => "{$dep->name} {$dep->surname}",
                'steps' => $dep->tasks_ongoing_count,
                'href' => image($dep->avatar)
            ];
        }
    }

    public function render()
    {
        return view('components.widgets.taskDone-widget');
    }
}
