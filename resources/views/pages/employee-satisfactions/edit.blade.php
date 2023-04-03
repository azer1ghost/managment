@extends('layouts.main')

@section('title', trans('translates.navbar.employee_satisfaction'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('employee-satisfaction.index')">
            @lang('translates.navbar.employee_satisfaction')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method !== 'POST')
                @lang('translates.employee_satisfactions.types.' . $data->getAttribute('type'))
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="type" value="{{request()->get('type') ?? $data->getAttribute('type')}}">
            @if((request()->get('type') == '3' || $data->getAttribute('type') == 3))
                <div class="row mr-0">
                        <div class="form-group col-md-6">
                            <label for="employee">@lang('translates.columns.user')</label>
                            <select class="form-control" name="employee" title="@lang('translates.filters.select')">
                            <option value=""> @lang('translates.general.user_select') </option>
                                @foreach($users as $user)
                                    <option
                                        @if($user->getAttribute('id') == $data->getAttribute('employee')) selected @endif value="{{$user->getAttribute('id')}}"> {{$user->getAttribute('fullname_with_position')}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    <div class="form-group col-md-6">
                        <label for="department_id">@lang('translates.fields.department')</label>
                        <select class="form-control" name="department_id">
                        <option value=""> @lang('translates.general.department_select') </option>
                            @foreach($departments as $department)
                                <option
                                    @if($department->getAttribute('id') == $data->getAttribute('department_id')) selected @endif value="{{$department->getAttribute('id')}}"> {{$department->getAttribute('name')}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        @if((request()->get('type') == '3' || $data->getAttribute('type') == 3))
            <p> @lang('translates.employee_satisfactions.content-3')</p>
        @elseif((request()->get('type') == '2' || $data->getAttribute('type') == 2))
            <p>@lang('translates.employee_satisfactions.content-2')</p>
        @else
            <p>@lang('translates.employee_satisfactions.content-1')</p>
        @endif
        <textarea id="summernote" name="content"  aria-label="content">{{$data->getAttribute('content')}}</textarea>
        @if(request()->get('type') == '3' || $data->getAttribute('type') == 3)
            <div class="row mt-3">
                <x-input::text name="activity" :label="trans('translates.employee_satisfactions.activity')" :value="$data->getAttribute('activity')" width="6"/>
                @if(auth()->user()->hasPermission('measure-employeeSatisfaction'))
                    <div class="custom-control col-6">
                        <label for="deadline">@lang('translates.columns.deadline')</label>
                        <input type="date" name="deadline" id="deadline" class="form-control deadline" label="@lang('translates.columns.deadline')" value="{{$data->getAttribute('deadline')}}"/>
                    </div>
                @endif
            </div>
            @if ($method !== 'POST')
            <div class="custom-control custom-switch is_enough">
                <input type="checkbox" name="is_enough" class="custom-control-input" id="is_enough" @if($data->getAttribute('is_enough')) checked @endif>
                <label class="custom-control-label" for="is_enough">@lang('translates.employee_satisfactions.is_enough')</label>
            </div>

            <div class="custom-control custom-switch more_time">
                <input type="checkbox" name="more_time" class="custom-control-input" id="more_time" @if($data->getAttribute('more_time')) checked @endif>
                <label class="custom-control-label" for="more_time">@lang('translates.employee_satisfactions.more_time')</label>
            </div>
                <x-input::textarea name="reason" class="reason" :value="$data->getAttribute('reason')"  :label="trans('translates.employee_satisfactions.reason')" width="12" rows="4"/>
                <x-input::textarea name="result" class="result" :value="$data->getAttribute('result')"  :label="trans('translates.employee_satisfactions.result')" width="12" rows="4"/>

            @endif

        @endif
        @if($method !== 'POST')
            <div class="row">
                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-status">@lang('translates.general.status_choose')</label>
                    <select name="status" id="data-status" class="form-control">
                        <option disabled >@lang('translates.general.status_choose')</option>
                        @foreach($statuses as $key => $status)
                            <option
                                    @if(optional($data)->getAttribute('status') === $status ) selected @endif value="{{$status}}">
                                @lang('translates.employee_satisfactions.statuses.' . $key)
                            </option>
                        @endforeach
                    </select>
                </div>
                <x-input::text name="effectivity" :label="trans('translates.employee_satisfactions.effectivity')" :value="$data->getAttribute('effectivity')" width="6"/>
            </div>
        @endif
        @if($data->status == 5)
            <x-input::textarea name="note" class="note" :value="$data->getAttribute('note')"  :label="trans('translates.fields.note')" width="12" rows="4"/>
        @endif
    @if($action)
            <x-input::submit :value="trans('translates.buttons.save')"/>
        @endif
    </form>
@endsection

@section('scripts')
        <script>
            $('#summernote').summernote({
                height: 250,
                minHeight: null,
                maxHeight: null,
                focus: true
            });
        </script>

    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif

    @if(!is_null($data->getAttribute('deadline')))
        <script>
            // $(".deadline").prop("readonly", true)
        </script>
    @endif
    @if ($method !== 'POST')
            <script>
                // $('#is_enough').attr('checked',true)
                $('.more_time').hide()
                $('.reason').hide()
                $('.result').hide()


                $('#is_enough').change(function()
                {
                    if ($(this).prop('checked')) {
                        $('.more_time').hide()
                        $('.result').show()
                        $(".reason").hide()
                    }
                    else
                        $('.result').hide(),
                        $('.more_time').show()
                })

                $('#more_time').change(function()
                {
                    if ($(this).prop('checked')) {
                        $('.is_enough').hide()
                        // $(".deadline").prop("readonly", false);
                            $(".reason").hide()
                    }
                    else
                        $('.result').hide(),
                        $('.is_enough').show(),
                        $(".reason").show()
                        // $(".deadline").prop("readonly", true)
                })
            </script>
        @endif
    @if(!is_null($data->getAttribute('reason')))
        <script>
            $('.reason').show()
            $('.more_time').hide()
            $('.is_enough').hide()
        </script>
    @endif
        @if(!is_null($data->getAttribute('result')))
        <script>
            $('.result').show()
            $('.more_time').hide()
            $('.is_enough').hide()
        </script>
    @endif


@endsection
