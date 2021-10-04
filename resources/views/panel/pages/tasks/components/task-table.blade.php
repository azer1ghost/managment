<form id="taskFilterForm">
    <div class="row d-flex justify-content-between mb-2">
        <div class="col-12 col-md-3">
            <div class="input-group mb-3">
                <input type="search" placeholder="Task name" wire:model.defer="search" class="form-control">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="input-group mb-3">
                <input type="search" placeholder="Search users" wire:model.defer="filters.user" class="form-control">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="input-group mb-3">
                <select class="form-control" wire:model.defer="filters.department">
                    <option value="">@lang('translates.fields.department') @lang('translates.placeholders.choose')</option>
                    @foreach ($departments as $department)
                        <option value="{{$department->id}}">{{$department->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="input-group mb-3">
                <select class="form-control" wire:model.defer="filters.status">
                    <option value="">@lang('translates.fields.status.key') @lang('translates.placeholders.choose')</option>
                    @foreach ($statuses as $status)
                        <option value="{{$status}}">@lang('translates.fields.status.options.' . $status)</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-3 py-2">
            <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
        </div>
        @can('create', App\Models\Task::class)
            <div class="col-2 py-2">
                <a class="btn btn-outline-success float-right" href="{{route('tasks.create')}}">@lang('translates.buttons.create')</a>
            </div>
        @endcan
        <div class="col-12">
            <table class="table table-responsive-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Priority</th>
                    <th scope="col">Status</th>
                    <th scope="col">Department</th>
                    <th scope="col">User</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$task->getAttribute('name')}}</td>
                        <td>{{ucfirst($task->getAttribute('priority'))}}</td>
                        <td>{{str_title($task->getAttribute('status'))}}</td>
                        <td>{{$task->taskable->getClassShortName() == 'department' ? $task->taskable->getAttribute('name') : $task->taskable->getRelationValue('department')->getAttribute('name')}}</td>
                        <td>{{$task->taskable->getClassShortName() == 'user' ? $task->taskable->getAttribute('fullname') : 'Ümumi' }}</td>
                        <td>
                            <div class="btn-sm-group">
                                @can('view', $task)
                                    <a href="{{route('tasks.show', $task)}}" class="btn btn-sm btn-outline-primary">
                                        <i class="fal fa-eye"></i>
                                    </a>
                                @endcan
                                @can('update', $task)
                                    <a href="{{route('tasks.edit', $task)}}" class="btn btn-sm btn-outline-success">
                                        <i class="fal fa-pen"></i>
                                    </a>
                                @endcan
                                @can('delete', $task)
                                    <a href="{{route('tasks.destroy', $task)}}" delete data-name="{{$task->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                        <i class="fal fa-trash"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <th colspan="7">
                            <div class="row justify-content-center m-3">
                                <div class="col-7 alert alert-danger text-center" task="alert">Empty for now</div>
                            </div>
                        </th>
                    </tr>
                @endforelse
                <div wire:loading.delay>
                    <div
                            class="d-flex justify-content-center align-items-center"
                            style="position: absolute;
                               top: 0;left: 0;
                               width: 100%;height: 100%;
                               background: rgba(0, 0, 0, 0.3);z-index: 999;"
                    >
                        <h3 class="text-white">@lang('translates.loading')...</h3>
                    </div>
                </div>
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <div class="float-right">
                {{$tasks->links()}}
            </div>
        </div>
    </div>
</form>
@section('scripts')
    <script>
        $('#taskFilterForm').submit(function (e){
            e.preventDefault();
            Livewire.emit('updateFilter')
        });
    </script>
@endsection