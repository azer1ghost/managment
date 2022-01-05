@extends('layouts.main')

@section('title', __('translates.navbar.task'))

@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.task')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <button class="btn btn-outline-success" onclick="showFilter()">
        <i class="far fa-filter"></i> @lang('translates.buttons.filter_open')
    </button>

    <form>
        <div class="row d-flex mb-2">
            <div id="showenFilter" class="mb-3" @if(request()->has('must_start_at')) style="display:block;" @else style="display:none;" @endif>

                <div class="col-12 mt-3">
                    <div class="row m-0">

                        <div class="col-12 col-md-3">
                            <input class="form-control" id="start-daterange" type="text" readonly name="must_start_at" value="{{$filters['must_start_at']}}">
                            <input type="checkbox" name="check_start_daterange" id="check_start_daterange" @if(request()->has('check_start_daterange')) checked @endif> <label for="check_start_daterange">@lang('translates.filters.filter_by')</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="input-group mb-3">
                                <input type="search" placeholder="@lang('translates.placeholders.task_name')" name="search" value="{{request()->get('search')}}" class="form-control">
                            </div>
                        </div>

                        @if(\App\Models\Task::userCanViewAll() || \App\Models\Task::userCanViewDepartmentTasks())
                            <div class="col-12 col-md-3">
                                <div class="input-group mb-3">
                                <select id="userFilter" class="select2 form-control" name="user_id">
                                    <option value="">@lang('translates.filters.select')</option>
                                    @foreach($users as $user)
                                        <option
                                            @if($user->getAttribute('id') == $filters['user_id']) selected @endif
                                            value="{{$user->getAttribute('id')}}">
                                            {{$user->getAttribute('fullname_with_position')}}
                                        </option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        @endif

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
                            <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i> @lang('translates.buttons.filter')</button>
                            <a href="{{route('tasks.index')}}" class="btn btn-outline-danger"><i
                                        class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                        </div>
            </div>
            </div>
            </div>
            <div class="col-8 pt-2 d-flex align-items-center">
                <p class="mb-0"> @lang('translates.total_items', ['count' => $tasks->count(), 'total' => $tasks->total()])</p>
                <div class="input-group col-md-3">
                    <select name="limit" class="custom-select" id="size">
                        @foreach([25, 50, 100, 250] as $size)
                            <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group col-md-3">
                        <select class="form-control" name="type">
                            @foreach ($types as $index => $type)
                                <option @if ($filters['type'] == $index) selected @endif value="{{$index}}">@lang('translates.tasks.types.' . $type)</option>
                            @endforeach
                        </select>
                </div>
            </div>
            @can('create', App\Models\Task::class)
                <div class="col-4 p-0 pr-3 pb-3 mt-4">
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
                        <tr @if($task->getAttribute('must_end_at') < now() && is_null($task->getAttribute('done_at')))
                            style="background: rgba(255,182,191,0.58)"
                            title="@lang('translates.columns.expired')"
                            data-toggle="tooltip"
                            @elseif($task->getAttribute('must_end_at') > now() && is_null($task->getAttribute('done_at')) && $task->getAttribute('status') == $task::TO_DO)
                            style="background:#fae4a9"
                           @elseif($task->getAttribute('must_end_at') > now() && is_null($task->getAttribute('done_at')) && $task->getAttribute('status') == $task::IN_PROGRESS)
                            style="background:#96dee9"
                           @endif
                        >
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$task->getAttribute('name')}}</td>
                            <td style="font-weight: 700;max-width: 100%;white-space: nowrap;" class="task-priority priority-{{$task->getAttribute('priority')}}">@if($task->getAttribute('priority') === '3') <i class="fas fa-exclamation-circle"></i> @endif @lang('translates.fields.priority.options.' . $task->getAttribute('priority'))</td>
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
                                @if ($task->status == $task::TO_DO)
                                    @lang('translates.tasks.not_started')
                                @elseif ($task->status != 'done' && ($task->all_tasks_count == 0 || $task->done_tasks_count == 0))
                                    @lang('translates.tasks.is_executing')
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        function showFilter() {
            var x = document.getElementById("showenFilter");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
        $('select[name="limit"]').change(function () {
            this.form.submit();
        });
        $('select[name="type"]').change(function () {
            this.form.submit();
        });

        $(function () {
            $('#start-daterange').daterangepicker({
                    opens: 'left',
                    locale: {
                        format: "YYYY/MM/DD",
                    },
                    maxDate: new Date(),
                }, function (start, end, label) {
                }
            );
        });
        const select2 = $('.select2');
        const clientFilter = $('.client-filter');

        $('select[name="limit"]').change(function () {
            $(this).form().submit();
        });

        select2.select2({
            theme: 'bootstrap4',
        });

        select2.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

        function select2RequestFilter(el, url){
            el.select2({
                placeholder: "Search",
                minimumInputLength: 3,
                // width: 'resolve',
                theme: 'bootstrap4',
                focus: true,
                ajax: {
                    delay: 500,
                    url: url,
                    dataType: 'json',
                    type: 'GET',
                    data: function (params) {
                        return {
                            search: params.term,
                        }
                    }
                }
            })

            el.on('select2:open', function (e) {
                document.querySelector('.select2-search__field').focus();
            });
        }

    </script>
@endsection