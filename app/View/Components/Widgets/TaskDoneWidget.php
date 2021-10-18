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

        $users = User::isActive();

        $usersByDoneStatus = $users
            ->withCount([
                'tasks as tasks_done_count' => fn($q) => $q->where('status', 'done')
            ])
            ->orderBy('tasks_done_count', 'asc')
            ->limit(6)
            ->get(['name', 'surname', 'avatar']);

        $usersByOngoingStatus = $users
            ->withCount([
                'tasks as tasks_ongoing_count' => fn($q) => $q->where('status', '!=', 'done')
            ])
            ->orderBy('tasks_ongoing_count', 'asc')
            ->limit(6)
            ->get(['name', 'surname', 'avatar']);

        $departments = Department::isActive();

        $departmentsByDoneStatus = $departments
            ->withCount([
                'tasks as tasks_done_count' => fn($q) => $q->where('status', 'done')
            ])
            ->orderBy('tasks_done_count', 'asc')
            ->limit(6)
            ->get(['name']);

        $departmentsByOngoingStatus = $departments
            ->withCount([
                'tasks as tasks_ongoing_count' => fn($q) => $q->where('status', '!=', 'done')
            ])
            ->orderBy('tasks_ongoing_count', 'asc')
            ->limit(6)
            ->get(['name']);


        // done and ongoing tasks of users
        foreach ($usersByDoneStatus as $user) {
            $this->results['done']['users'][] = [
                'name' => "{$user->name} {$user->surname}",
                'steps' => $user->tasks_done_count,
                'href' => image($user->avatar)
            ];
        }
        foreach ($usersByOngoingStatus as $user) {
            $this->results['ongoing']['users'][] = [
                'name' => "{$user->name} {$user->surname}",
                'steps' => $user->tasks_ongoing_count,
                'href' => image($user->avatar)
            ];
        }

        // done and ongoing tasks of departments
        foreach ($departmentsByDoneStatus as $dep) {
            $this->results['done']['departments'][] = [
                'name' => "{$dep->name}",
                'steps' => $dep->tasks_done_count,
                'href' => image('no_image')
            ];
        }
        foreach ($departmentsByOngoingStatus as $dep) {
            $this->results['ongoing']['departments'][] = [
                'name' => "{$dep->name} {$dep->surname}",
                'steps' => $dep->tasks_ongoing_count,
                'href' => image($dep->avatar)
            ];
        }
//        dd($this->results);
    }

    public function render()
    {
        return view('components.widgets.taskDone-widget');
    }
}
