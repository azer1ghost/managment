<?php

namespace App\Http\Livewire;
use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class TaskTable extends Component
{
    use WithPagination;

    public string $search = '';
    public array $filters = [
        'department' => '',
        'user' => '',
        'status' => ''
    ];

    public Collection $departments;

    public array $statuses;

    protected string $paginationTheme = 'bootstrap';

    protected $listeners = ['updateFilter' => 'updateFilter'];


    public function mount()
    {
        $this->departments = Department::get(['id', 'name']);
        $this->statuses = Task::statuses();
    }

    public function updateFilter()
    {
        $this->render();
    }

    public function render()
    {
        return view('panel.pages.tasks.components.task-table', [
            'tasks' => Task::with(['inquiry'])
                        ->when($this->search, fn ($query) => $query->where('name', 'like', "%". $this->search ."%"))
                        ->when($this->filters['department'], fn ($query) => $query
                            ->whereHasMorph('taskable', [Department::class], fn ($q) => $q->where('id', $this->filters['department'])))
                        ->when($this->filters['user'], fn ($query) => $query
                            ->whereHasMorph('taskable', [User::class], fn ($q) => $q
                                ->where('surname', 'like', $this->filters['user'])
                                ->orWhere('name', 'like', $this->filters['user'])))
                        ->when($this->filters['status'], fn ($query) => $query->where('status', $this->filters['status']))
                        ->paginate(10)]);
    }
}