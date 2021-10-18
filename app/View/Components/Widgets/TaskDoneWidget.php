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
                    'tasks as tasks_ongoing_count' => fn($q) => $q->where('status', '!=', 'done'),
                ])
                ->orderBy('tasks_ongoing_count', 'asc')
                ->limit(6)
                ->get(['name', 'surname', 'avatar']);

        $departments = Department::isActive()->withCount([
                        'tasks as tasks_ongoing_count' => fn($q) => $q->where('status', '!=', 'done'),
                    ])
                    ->orderBy('tasks_ongoing_count', 'asc')
                    ->limit(6)
                    ->get(['name']);

        foreach ($users as $user) {
            $this->results['users'][] = [
                'name' => "{$user->name} {$user->surname}",
                'steps' => $user->tasks_ongoing_count,
                'href' => image($user->avatar)
            ];
        }

        foreach ($departments as $department) {
            $this->results['departments'][] = [
                'name' => "{$department->name}",
                'steps' => $department->tasks_ongoing_count,
                'href' => image($department->avatar)
            ];
        }
    }

    public function render()
    {
        return view('components.widgets.taskDone-widget');
    }
}
