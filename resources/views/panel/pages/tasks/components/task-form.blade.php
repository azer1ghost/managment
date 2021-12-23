@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

<form action="{{$action}}" id="createTaskForm" method="POST" class="tab-content form-row mt-4 mb-5">
    @if(is_null($method))
        @can('update', $task)
            <div class="col-12 mt-0 mb-4">
                <a class="btn btn-outline-success" href="{{route('tasks.edit', $task)}}">Edit</a>
            </div>
        @endcan
    @endif
    @csrf
    @method($method)
    <input type="hidden" wire:ignore name="list_id" value="{{request()->get('list_id')}}">
    <div wire:loading.delay class="col-12">
        <div style="position: absolute;right: 0;top: -25px">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>

    <div wire:offline class="col-12">
        <div class="text-danger" title="Internet connection loss" style="position: absolute;right: 0;top: -25px">
            <img src="{{asset('assets/images/svgs/exclamation-triangle-solid.svg')}}" alt="internet-loss" height="25">
        </div>
    </div>

    @if($method == "POST")
    <input wire:ignore type="hidden" name="inquiry_id" value="{{request()->get('inquiry_id')}}">
    @endif
    <x-input::text wire:ignore name="name" required :value="request()->get('name') ?? optional($task)->getAttribute('name') ?? old('name')"  :label="__('translates.tasks.label.name')"   width="6" class="pr-3" />

    <div wire:ignore class="form-group col-12 col-md-6 mb-3 mb-md-0" >
        @php($task_dates = optional($task)->getAttribute('must_start_at') && optional($task)->getAttribute('must_end_at') ?  optional($task)->getAttribute('must_start_at') . ' - ' .  optional($task)->getAttribute('must_end_at') : '')
        <label for="task_dates">{{__('translates.placeholders.range')}}</label>
        <input type="text" id="task_dates" readonly placeholder="{{__('translates.placeholders.range')}}" class="form-control @error('task_dates') is-invalid @enderror" name="task_dates" value="{{$task_dates}}">
        @error('task_dates')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group col-md-3">
        <label>{{__('translates.fields.priority.key')}}</label>
        <select class="form-control @error('priority') is-invalid @enderror" name="priority" wire:model="selected.priority">
            <option value="null" disabled selected>{{__('translates.fields.priority.key')}} {{__('translates.placeholders.choose')}}</option>
            @foreach($priorities as $priority)
                <option value="{{$priority}}">@lang("translates.fields.priority.options.{$priority}")</option>
            @endforeach
        </select>
        @error('priority')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    @if($task)
        <div class="form-group col-md-3">
            <label>{{__('translates.fields.status.key')}}</label>
            <select class="form-control @error('status') is-invalid @enderror" id="task-status" name="status" @if (is_null($action)) onfocus="this.oldValue = this.value" onchange="taskStatusHandler(this.oldValue, this.value)" @endif wire:model="selected.status">
                <option value="null" disabled selected>{{__('translates.fields.status.key')}} {{__('translates.placeholders.choose')}}</option>
                @foreach($statuses as $status)
                    @if ($status == 'done')
                        @if(optional($task)->getAttribute('status') == \App\Models\Task::TO_DO)
                            @continue
                        @endif
                        <option value="{{$status}}" @if (!$task->isFinished() ) disabled @endif>@lang("translates.fields.status.options.{$status}")</option>
                    @else
                        <option value="{{$status}}">@lang("translates.fields.status.options.{$status}")</option>
                    @endif
                @endforeach
            </select>
            @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    @endif

    <div class="form-group col-md-3">
        <label>{{__('translates.fields.department')}}</label>
        <select class="form-control @error('department') is-invalid @enderror" name="department" wire:model="selected.department">
            <option value="null" disabled selected>{{__('translates.fields.department')}} {{__('translates.placeholders.choose')}}</option>
            @foreach($departments as $depart)
                <option value="{{ $depart->getAttribute('id') }}">{{ $depart->getAttribute('name') }}</option>
            @endforeach
        </select>
        @error('department')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    @if (!is_null($this->department) &&
        ($this->department->getAttribute('id') == auth()->user()->getRelationValue('department')->getAttribute('id') ||
        auth()->user()->isDirector()))
         <div class="form-group col-md-3">
            <label>{{__('translates.fields.user')}}</label>
            <select class="form-control @error('user') is-invalid @enderror" id="task-user" name="user" @if (is_null($action)) onfocus="this.oldValue = this.value" onchange="taskUserHandler(this.oldValue, this.value)" @endif wire:model="selected.user">
                <option value="null" disabled selected>{{__('translates.fields.user')}} {{__('translates.placeholders.choose')}}</option>
                @foreach($this->department->users()->isActive()->get(['id', 'name', 'surname']) as $user)
                    <option value="{{ $user->getAttribute('id') }}">{{ $user->getAttribute('fullname') }}</option>
                @endforeach
            </select>
            @error('user')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         </div>
    @endif

    <x-input::textarea name="note" :value="optional($task)->getAttribute('note')"  :label="__('translates.fields.note')"   width="12" class="" rows="4"/>

    @if($action)
        <div class="col-12">
            <button class="btn btn-outline-primary float-right">Save</button>
        </div>
    @endif
</form>

@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        function loadJs() {
            @if(is_null($action))
            $("#createTaskForm :input").attr("disabled", true);
            @endif

            @if($task && $task->canManageLists() && $task->getAttribute('status') != 'done')
            $("select[name='status']").attr("disabled", false);
            @endif

            @if($task && $task->canManageTaskable() && $task->getAttribute('status') != 'done' && auth()->user()->can('update', $task))
            $("select[name='user']").attr("disabled", false);
            @endif

            $(".inquiry :input").attr("disabled", true);
        }

        $(function() {
            $('input[name="task_dates"]').daterangepicker({
                    opens: 'left',
                    locale: {
                        format: "YYYY-MM-DD HH:mm",
                    },
                    timePicker: true,
                    timePicker24Hour: true,
                }, function(start, end, label) {}
            );
        });

        loadJs();

        function alertHandler(event){
            $.alert({
                type:    event?.detail?.type,
                title:   event?.detail?.title,
                content: event?.detail?.message,
                theme: 'modern',
                typeAnimated: true
            });
            loadJs();
        }
        addEventListener('alert', alertHandler);

        addEventListener('alertChange', function (event){
            $.confirm({
                title: event?.detail?.title,
                content: event?.detail?.message,
                autoClose: 'confirm|8000',
                icon: 'fa fa-question',
                type: event?.detail?.type,
                theme: 'modern',
                typeAnimated: true,
                buttons: {
                    confirm: function () {
                        Livewire.emit(`${event?.detail?.selected}Changed`, event?.detail?.old, event?.detail?.new)
                    },
                    cancel: function () {
                        $(`#task-${event?.detail?.selected}`).val(event?.detail?.old);
                    },
                }
            });
        });

        function taskStatusHandler(oldVal, newVal){
            Livewire.emit('statusChanging', oldVal, newVal)
        }

        function taskUserHandler(oldVal, newVal){
            Livewire.emit('userChanging', oldVal, newVal)
        }
    </script>
@endpush



