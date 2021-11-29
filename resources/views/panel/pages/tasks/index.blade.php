@extends('layouts.main')

@section('title', __('translates.navbar.task'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.task')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form>
        <div class="row d-flex mb-2">
            <div class="col-12 col-md-3">
                <div class="input-group mb-3">
                    <input type="search" placeholder="@lang('translates.placeholders.task_name')" name="search" value="{{request()->get('search')}}" class="form-control">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="input-group mb-3">
                    <input type="search" placeholder="@lang('translates.placeholders.search_users')" name="user" value="{{request()->get('user')}}" class="form-control">
                </div>
            </div>

            @if(\App\Models\Task::userCanViewAll())
                <div class="col-12 col-md-3">
                    <div class="input-group mb-3">
                        <select class="form-control" name="department">
                            <option value="">@lang('translates.fields.department') @lang('translates.placeholders.choose')</option>
                            @foreach ($departments as $department)
                                <option @if ($department->id == request()->get('department')) selected @endif value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <div class="col-12 col-md-3">
                <div class="input-group mb-3">
                    <select class="form-control" name="status">
                        <option value="">@lang('translates.fields.status.key') @lang('translates.placeholders.choose')</option>
                        @foreach ($statuses as $status)
                            <option @if ($status == request()->get('status')) selected @endif value="{{$status}}">@lang('translates.fields.status.options.' . $status)</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="input-group mb-3">
                    <select class="form-control" name="priority">
                        <option value="">@lang('translates.fields.priority.key') @lang('translates.placeholders.choose')</option>
                        @foreach ($priorities as $priority)
                            <option @if ($priority == request()->get('priority')) selected @endif value="{{$priority}}">@lang('translates.fields.priority.options.' . $priority)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="input-group mb-3">
                    <select class="form-control" name="type">
                        @foreach ($types as $index => $type)
                            <option @if (request()->get('type') == $index) selected @endif value="{{$index}}">@lang('translates.tasks.types.' . $type)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-3 col-md-6 p-0 pl-3 pb-3">
                <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
            </div>
            <div class="col-6 pt-2 d-flex align-items-center">
                <p class="mb-0"> @lang('translates.total_items', ['count' => $tasks->count(), 'total' => $tasks->total()])</p>
                <div class="input-group col-md-3">
                    <select name="limit" class="custom-select" id="size">
                        @foreach([25, 50, 100, 250] as $size)
                            <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @can('create', App\Models\Task::class)
                <div class="col-6 p-0 pr-3 pb-3 mt-3">
                    <a class="btn btn-outline-success float-right" href="{{route('tasks.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.columns.priority')</th>
                        <th scope="col">@lang('translates.columns.status')</th>
                        <th scope="col">@lang('translates.columns.created_by')</th>
                        <th scope="col">@lang('translates.columns.department')</th>
                        <th scope="col">@lang('translates.columns.user')</th>
                        <th scope="col">@lang('translates.columns.deadline')</th>
                        <th scope="col">@lang('translates.fields.status.options.done')</th>
                        <th scope="col" style="min-width: 150px; width: 150px;">@lang('translates.columns.stage')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$task->getAttribute('name')}}</td>
                            <td style="font-weight: 700;" class="task-priority {{$task->getAttribute('priority')}}">@lang('translates.fields.priority.options.' . $task->getAttribute('priority'))</td>
                            <td style="font-weight: 700;">@lang('translates.fields.status.options.' . $task->getAttribute('status'))</td>
                            <td>{{$task->getRelationValue('user')->getAttribute('fullname_with_position')}}</td>
                            <td>{{$task->taskable->getClassShortName() == 'department' ? $task->taskable->getAttribute('name') : $task->taskable->getRelationValue('department')->getAttribute('name')}}</td>
                            <td>
                                {!! $task->taskable->getClassShortName() == 'user' ?
                                    $task->taskable->getAttribute('fullname_with_position') . ($task->taskable->getAttribute('disabled_at') ? ' <span class="text-danger">(' . __('translates.disabled') . ')</span>' : '') :
                                    __('translates.navbar.general')
                                 !!}
                            </td>
                            <td>
                                {{$task -> getAttribute('must_end_at')}}
                            </td>
                            <td>
                                {{$task -> getAttribute('done_at')}}
                            </td>
                            <td>
                                @if ($task->status == 'to_do')
                                    @lang('translates.tasks.not_started')
                                @elseif ($task->status != 'done' && ($task->all_tasks_count == 0 || $task->done_tasks_count == 0))
                                @elseif ($task->status == 'done')
                                    <i class="fa fa-check-circle text-success" style="font-size: 20px"></i>
                                @else
                                    <div class="progress bg-secondary">
                                        @php($percentage = $task->all_tasks_count == 0 ? 0 : (round(($task->done_tasks_count/$task->all_tasks_count), 2) * 100))
                                        <div class="progress-bar progress-bar-striped bg-success task-priority-bg {{$task->getAttribute('priority')}} progress-bar-animated" role="progressbar" style="width: {{$percentage}}%">{{$percentage}}%</div>
                                    </div>
                                    <span>{{$task->done_tasks_count}}</span>/{{$task->all_tasks_count}}
                                @endif
                            </td>
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
                            <th colspan="10">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" task="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="float-right">
                    {{$tasks->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        $('select[name="limit"]').change(function () {
            this.form.submit();
        });
        $('select[name="type"]').change(function () {
            this.form.submit();
        });
    </script>
@endsection