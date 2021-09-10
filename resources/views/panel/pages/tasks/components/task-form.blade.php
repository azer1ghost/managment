@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
<form action="{{$action}}" id="createForm" method="POST" class="tab-content form-row mt-4 mb-5">
    @csrf
    @method($method)

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

    <input type="hidden" name="id" value="{{optional($task)->getAttribute('id')}}">

    <x-input::text  name="name"  :value="optional($task)->getAttribute('name')"  label="Task name"  width="6" class="pr-3" />

    <div wire:ignore class="form-group col-12 col-md-6 mb-3 mb-md-0" >
        @php($task_dates = optional($task)->getAttribute('must_start_at') && optional($task)->getAttribute('must_end_at') ?  optional($task)->getAttribute('must_start_at') . ' - ' .  optional($task)->getAttribute('must_end_at') : '')
        <label for="task_dates">{{__('translates.placeholders.range')}}</label>
        <input type="text" id="task_dates" placeholder="{{__('translates.placeholders.range')}}" class="form-control @error('task_dates') is-invalid @enderror" name="task_dates" value="{{$task_dates}}">
        @error('task_dates')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group col-md-3">
        <label>{{__('translates.fields.priority.key')}}</label>
        <select class="form-control @error('priority') is-invalid @enderror" name="priority" wire:model="priority">
            <option value="" disabled selected>{{__('translates.fields.priority.key')}} {{__('translates.placeholders.choose')}}</option>
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

    <div class="form-group col-md-3">
        <label>{{__('translates.fields.status.key')}}</label>
        <select class="form-control @error('status') is-invalid @enderror" name="status" wire:model="status">
            <option value="" disabled selected>{{__('translates.fields.status.key')}} {{__('translates.placeholders.choose')}}</option>
            @foreach($statuses as $status)
                <option value="{{$status}}">@lang("translates.fields.status.options.{$status}")</option>
            @endforeach
        </select>
        @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group col-md-3">
        <label>{{__('translates.fields.department')}}</label>
        <select class="form-control @error('department') is-invalid @enderror" name="department" wire:model="department">
            <option value="" disabled selected>{{__('translates.fields.department')}} {{__('translates.placeholders.choose')}}</option>
            @foreach($departments as $department)
                <option value="{{ $department->getAttribute('id') }}">{{ $department->getAttribute('name') }}</option>
            @endforeach
        </select>
        @error('department')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    @if ($users->isNotEmpty())
        <div class="form-group col-md-3">
            <label>{{__('translates.fields.user')}}</label>
            <select class="form-control @error('user') is-invalid @enderror" name="user" wire:model="user">
                <option value="" disabled selected>{{__('translates.fields.user')}} {{__('translates.placeholders.choose')}}</option>
                @foreach($users as $user)
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

    <x-input::textarea name="note" :value="optional($task)->getAttribute('note')"  label="Note"   width="12" class="pr-3" rows="4"/>

    @if($action)
        <div class="col-12">
            <button class="btn btn-outline-primary float-right">Save</button>
        </div>
    @endif
</form>

@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        @if(is_null($action))
            $('input').attr('readonly', true)
            $('select').attr('disabled', true)
            $('textarea').attr('readonly', true)
        @endif

        $(function() {
            $('input[name="task_dates"]').daterangepicker({
                    opens: 'left',
                    locale: {
                        format: "YYYY-MM-DD HH:mm:ss",
                    },
                    timePicker: true,
                    timePicker24Hour: true,
                    minDate: new Date(),
                }, function(start, end, label) {}
            );
        });
    </script>
@endsection



