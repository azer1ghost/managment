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
                {{$data->getRelationValue('user')->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf

            @if(request()->get('type') == '3' || $data->getAttribute('name') == 3)
                <p class="text-muted mb-2">@lang('translates.fields.employment')</p>
                <hr class="my-2">
                <div class="row mr-0">
                    <x-input::text  name="employee" :label="trans('translates.fields.name')" :value="$data->getAttribute('name')" width="6"/>
                    <x-input::select name="department_id" :label="trans('translates.fields.department')" value="" width="6"  class="pr-0" :options="$departments" />
                </div>
        @endif

{{--            @if(request()->get('type') == '3' || $data->getAttribute('name') == 3)--}}
            <textarea id="summernote" name="content" aria-label="content">{{$data->getAttribute('name')}}</textarea>
{{--            @elseif(request()->get('type') == '1' || $data->getAttribute('name') == 1)--}}

{{--            @else--}}

{{--            @endif--}}

        @if(request()->get('type') == '3' || $data->getAttribute('name') == 3)
            <x-input::text  name="activity" :label="trans('translates.employee_satisfactions.activity')" :value="$data->getAttribute('activity')" width="6"/>
            <x-input::date  name="deadline" :label="trans('translates.columns.deadline')" :value="$data->getAttribute('deadline')" width="6"/>
            <x-input::text  name="activity" :label="trans('translates.employee_satisfactions.activity')" :value="$data->getAttribute('activity')" width="6"/>
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

    <script>
        // const partnerId = $('#partner_id');
        // const userId = $('#user_id');
        //
        // if (userId.val() === '') $('.partner').show();
        // else $('.partner').hide();
        //
        // if (partnerId.val() === '') $('.user').show();
        // else $('.user').hide();
        //
        // userId.change(function () {
        //     if ($(this).val() === '') $('.partner').show();
        //     else $('.partner').hide();
        // });
        //
        // partnerId.change(function () {
        //     if ($(this).val() === '') $('.user').show();
        //     else $('.user').hide();
        // });
    </script>
@endsection
