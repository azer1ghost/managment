<?php

namespace App\Http\Livewire;
use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
        'status' => '',
        'priority' => ''
    ];

    public Collection $departments;

    public array $statuses, $priorities;

    protected string $paginationTheme = 'bootstrap';

    protected $listeners = ['updateFilter' => 'updateFilter'];


    public function mount()
    {
        $this->departments = Department::get(['id', 'name']);
        $this->statuses = Task::statuses();
        $this->priorities = Task::priorities();
    }

    public function updateFilter()
    {
        $this->render();
    }

    public function render()
    {
        return view('panel.pages.tasks.components.task-table', [
            'tasks' => Task::with(['inquiry'])->withCount([
                                'taskLists as all_tasks_count',
                                'taskLists as done_tasks_count' => fn (Builder $query) => $query->where('is_checked', true)
                                ])
                        ->when(!Task::userCanViewAll(), function ($query){
                            $query->whereHasMorph('taskable', [Department::class, User::class] , function ($q, $type){
                                if ($type === Department::class) {
                                    $q->where('id', auth()->user()->getRelationValue('department')->getAttribute('id'));
                                }else{
                                    $q->where('id', auth()->id());
                                }
                            });
                            $query->orWhere('user_id', auth()->id());
                        })
                        ->when($this->search, fn ($query) => $query->where('name', 'like', "%". $this->search ."%"))
                        ->where(function ($query)  {
                            foreach ($this->filters as $column => $value) {
                                $query->when($value, function ($query, $value) use ($column) {
                                    if ($column == 'department'){
                                        $query->whereHasMorph('taskable', [Department::class], fn ($q) => $q->where('id', $this->filters['department']));
                                    }else if ($column == 'user'){
                                        $query->whereHasMorph('taskable', [User::class], fn ($q) => $q
                                            ->where('surname', 'like', $this->filters['user'])
                                            ->orWhere('name', 'like', $this->filters['user']));
                                    }else{
                                        $query->where($column, $value);
                                    }
                                });
                            }
                        })
                        ->paginate(10)]);
    }
}