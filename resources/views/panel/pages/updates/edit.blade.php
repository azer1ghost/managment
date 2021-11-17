@extends('layouts.main')

@section('title', __('translates.navbar.update'))
@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('updates.index', ['type' => 'table'])">
            @lang('translates.navbar.update')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class="tab-content row mt-4" >
            <div class="form-group col-12">
                <div class="row">
                    <x-input::text  name="name"  :value="optional($data)->getAttribute('name')"  label="Update name"  width="6" class="pr-3" />
                    <div class="col-12 col-md-6 pr-3">
                        <label for="data-status">Update Status</label>
                        <select name="status" id="data-status" class="form-control">
                            <option value="" selected disabled>Select status</option>
                            @foreach($statuses as $index => $status)
                                <option @if(optional($data)->getAttribute('status') === $index) selected @elseif ($status['default']) selected @endif value="{{$index}}">{{$status['name']}}</option>
                            @endforeach
                        </select>
                        @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                    <div class="col-12 col-md-6 pr-3 mb-3">
                        <label for="data-parent_id">Update parent</label>
                        <select name="parent_id" id="data-parent_id" class="form-control">
                            <option value="" selected>Select parent</option>
                            @foreach($updates as $update)
                                <option
                                        @if(optional($data)->getAttribute('parent_id') === $update->id) selected
                                        @elseif (request()->get('parent_id') == $update->id) selected @endif
                                        value="{{$index}}"
                                >
                                    {{$update->name}} ({{$update->datetime}})
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div id="date-container">
                        <x-input::text name="datetime" :label="__('translates.fields.date')" value="{{optional($data)->getAttribute('datetime') ?? now()->format('Y-m-d')}}" type="text" width="12" class="pr-2" />
                    </div>
                    <div id="done-at-container">
                        <x-input::text name="done_at" label="Done at" value="{{optional($data)->getAttribute('done_at') ?? now()->format('Y-m-d H:i:s')}}" type="text" width="12" class="pr-2" />
                    </div>
                    <x-input::textarea name="content" :value="optional($data)->getAttribute('content')" label="Update content"  width="12" class="pr-3" />
                </div>
                <div id="create-child-btn">
                    @if(!is_null($data) && is_null(optional($data)->getAttribute('parent_id')))
                        @can('create', App\Models\Update::class)
                            <a class="btn btn-outline-success" target="_blank" href="{{route('updates.create', ['parent_id' => optional($data)->getAttribute('id')])}}">Create child</a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
    </form>
{{--    <div class="mt-5">--}}
{{--        @if($method != "POST")--}}
{{--            <livewire:commentable :commentable="$data" :url="str_replace('/edit', '', url()->current())"/>--}}
{{--        @endif--}}
{{--    </div>--}}
@endsection
@section('scripts')
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            $( "input[name='datetime']" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                showAnim: "slideDown",
            });

            $("input[name='done_at']").daterangepicker({
                    opens: 'left',
                    locale: {
                        format: "YYYY-MM-DD HH:mm:ss",
                    },
                    singleDatePicker: true,
                    timePicker: true,
                    timePicker24Hour: true,
                }, function(start, end, label) {}
            );

            function checkParent(parent = 'select[name="parent_id"]'){
                if($(parent).val().length === 0){
                    $('#date-container').show().find(':input').attr('disabled', false);
                    $('#create-child-btn').show();
                }else{
                    $('#date-container').hide().find(':input').attr('disabled', true);
                    $('#create-child-btn').hide();
                }
            }
            function checkStatus(status = 'select[name="status"]'){
                if($(status).val() === '5'){
                    $('#done-at-container').show().find(':input').attr('disabled', false);
                }else{
                    $('#done-at-container').hide().find(':input').attr('disabled', true);
                }
            }

            checkParent();
            checkStatus();

            $('select[name="parent_id"]').change(function (){
                checkParent();
            });
            $('select[name="status"]').change(function (){
                checkStatus();
            });

            @if(is_null($action))
                $('form :input').attr('disabled', true)
            @endif
        </script>
@endsection
